<?php
namespace HDNET\Importr\Service\Targets;

use HDNET\Importr\Domain\Model\Strategy;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use HDNET\Importr\Utility;

/**
 * Imports records from a .CSV file into the target table which you
 * can specify on the target section in your strategy.
 * If a record does not exist in the table, it will be inserted,
 * otherwise it will be just updated. No duplicates are created.
 *
 * complete example (strategy target):
 *
 * HDNET\Importr\Service\Targets\InsertUpdateTable:
 *   target_table: fe_users
 *   exclude_from_update:
 *     0: password
 *     1: first_name
 *     2: zip
 *   pid: 324
 *   identifier: username
 *   salt_password: 1
 *   mapping:
 *     0: username
 *     1: password
 *     2: usergroup
 *     3: name
 *     4: first_name
 *     5: address
 *     6: telephone
 *     7: email
 *     8: zip
 *     9: city
 *     10: company
 *
 * Example CSV:
 *
 * username;password;usergroup;name;first_name;address;telephone;email;zip;city;company
 * EduardFekete;PW123;3;Fekete;Eduard; Example 21; +049123456789;eduard.fekete@mbconnectline.de;91550;Feuchtwangen;MB Connect Line GmbH
 * HansVader;PW1234;3;Vader;Hans; Example 22; +049123456710;hans_vader@universe.com;99900;Universe;Hollywood Studios
 *
 * ------------------------------------------------------------------------------------------------
 *
 *   exclude_from_update:    the elements specified in this array, are never being updated
 *
 *   salt_password:          if set to 1 then passwords are being salted before they are stored in the database
 *
 * ------------------------------------------------------------------------------------------------
 * @author Eduard Fekete
 */
class InsertUpdateTable extends DbRecord implements TargetInterface
{
    /**
     * @var int
     */
    protected $identifierField;

    /**
     * @param Strategy $strategy
     */
    public function start(Strategy $strategy)
    {
        parent::start($strategy);
        if (!isset($this->getConfiguration()['identifier'])) {
            throw new \RuntimeException('Identifier field is missing!');
        }
        $identifier = $this->getConfiguration()['identifier'];
        $identifierField = array_search($identifier, $this->getConfiguration()['mapping']);
        if ($identifierField === false) {
            throw new \RuntimeException('Identifier field not found in mapping.');
        }
        $this->identifierField = $identifierField;
    }

    /**
     * Process every entry in the .csv
     *
     * @param array $entry
     *
     * @return int|void
     */
    public function processEntry(array $entry)
    {
        $record_exists = false;
        $entry_identifier = $entry[$this->identifierField];
        $records = $this->getRecords("*");
        $fieldName = $this->getConfiguration()['mapping'][$this->identifierField];

        foreach ($records as $record) {
            if ($record['deleted'] == 0) {
                if ($record[$fieldName] == $entry_identifier) {
                    $record_exists = true;
                    break;
                }
            } else {
                continue;
            }
        }

        if ($record_exists) {
            $this->updateRecord($entry);
            return TargetInterface::RESULT_UPDATE;
        }

        $this->insertRecord($entry);
        return TargetInterface::RESULT_INSERT;
    }

    /**
     *
     * Fetch all records from the target table, where the PID equals the PID specified
     * in the target section of the strategy
     *
     * @param $selectFields
     * @return array
     */
    protected function getRecords($selectFields)
    {
        static $records = [];
        if ($records) {
            return $records;
        }

        $fromTable = $this->getConfiguration()['target_table'];
        $whereStatement = "pid = '" . $this->getConfiguration()['pid'] . "'";

        $records = Utility::getDatabaseConnection()->exec_SELECTgetRows(
            $selectFields,
            $fromTable,
            $whereStatement
        );

        return $records;
    }

    /**
     * Insert record into the target table which you have specified in the target section of the strategy
     *
     * @param array $entry
     *
     * @return void
     */
    protected function insertRecord(array $entry)
    {
        $field_values = [];
        $into_table = $this->getConfiguration()['target_table'];

        foreach ($this->getConfiguration()["mapping"] as $key => $value) {
            $field_values[$value] = $entry[$key];
        }
        
        if ($this->getConfiguration()["salt_password"] == 1) {
            $field_values["password"] = $this->saltPassword($field_values["password"]);
        }

        $field_values['pid'] = $this->getConfiguration()['pid'];
        $time = time();
        $field_values['tstamp'] = $time;
        $field_values['crdate'] = $time;
        
        Utility::getDatabaseConnection()->exec_INSERTquery($into_table, $field_values);
    }

    /**
     * Update a record in the target table which you have specified in the
     * target section of the strategy (don't update the password)
     *
     * @param array $entry
     *
     * @return void
     */
    protected function updateRecord(array $entry)
    {
        $into_table = $this->getConfiguration()['target_table'];
        $fieldName = $this->getConfiguration()['mapping'][$this->identifierField];
        $whereStatement = "pid = '" . $this->getConfiguration()['pid'] . "' AND " . $fieldName . " = '" . $entry[$this->identifierField] . "'";

        $tmp_arr = [];

        foreach ($this->getConfiguration()["mapping"] as $key => $value) {
            $tmp_arr[$value] = $entry[$key];
        }
        
        if ($this->getConfiguration()["salt_password"] == 1) {
            $tmp_arr['password'] = $this->saltPassword($tmp_arr['password']);
        }
        
        $field_values = $this->duplicateArray($tmp_arr, $this->getConfiguration()['exclude_from_update']);
        $field_values['tstamp'] = time();

        Utility::getDatabaseConnection()->exec_UPDATEquery($into_table, $whereStatement, $field_values);
    }

    /**
     * This function creates a duplicate of a associative array and optionally removes
     * any entries which are also elements of a second array
     *
     * @param array $arr
     * @param array $exclude_arr
     *
     * @return array
     */
    protected function duplicateArray(array $arr, array $exclude_arr = null)
    {
        if (!is_array($exclude_arr)) {
            return $arr;
        }

        foreach ($arr as $key => $_) {
            if (in_array($key, $exclude_arr)) {
                unset($arr[$key]);
            }
        }

        return $arr;
    }
    
    /**
    * This function takes a password as argument, salts it and returns the new password.
    *
    * @param string $password
    *
    * @return string
    */
    protected function saltPassword($password)
    {
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('saltedpasswords')) {
            $saltedpasswordsInstance = \TYPO3\CMS\Saltedpasswords\Salt\SaltFactory::getSaltingInstance(null, 'FE');
            $password = $saltedpasswordsInstance->getHashedPassword($password);

            if ($this->isValidMd5($password)) {
                $password = 'M' . $password;
            }
        }

        return $password;
    }
    
    /**
    * This function checks if a password is in md5 format.
    *
    * @param string $md5
    *
    * @return int
    */
    protected function isValidMd5($md5 = '')
    {
        return preg_match('/^[a-f0-9]{32}$/', $md5);
    }

    /**
     *
     */
    public function end()
    {
        parent::end();
    }
}
