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
  *   model: TYPO3\CMS\Extbase\Domain\Model\FrontendUser
  *   repository: TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
  *   target_table: fe_users
  *   exclude_from_update:
  *     0: password
  *     1: first_name
  *     2: zip
  *   pid: 324
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
  * ------------------------------------------------------------------------------------------------
  * @author Eduard Fekete
  */

class InsertUpdateTable extends AbstractTarget implements TargetInterface
{
    /**
     * @var array
     */
    protected $table_records;

    /**
     * @var array
     */
    protected $configuration;

    /**
     * @var string
     */
    protected $firstTime = "1";

    /**
     * @var Strategy
     */
    protected $strategy;

    /**
     * @return array
     */
    public function getConfiguration()
    {
        $configuration = parent::getConfiguration();
        if (!isset($configuration['pid']) || !is_numeric($configuration['pid'])) {
            $configuration['pid'] = 0;
        }

        return $configuration;
    }

    /**
     * @param \HDNET\Importr\Domain\Model\Strategy $strategy
     *
     * @return void
     */
    public function start(Strategy $strategy)
    {
        $this->strategy = $strategy;
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
        $record_exists    = false;
        $entry_username   = $entry['0'];

        $this->configuration    = $this->getConfiguration();

        if ($this->firstTime == "1") {
            $this->firstTime = 0;
            $this->getRecords("*");
        }

        foreach ($this->table_records as $record) {
            if ($record['deleted'] == 0) {
                if ($record['username'] == $entry_username) {
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
        } else {
            $this->insertRecord($entry);
            return TargetInterface::RESULT_INSERT;
        }
    }

    /**
     *
     * Fetch all records from the target table, where the PID equals the PID specified
     * in the target section of the strategy
     *
     * @return void
     */
    public function getRecords($selectFields)
    {
        $fromTable        = $this->configuration['target_table'];
        $whereStatement   = "pid = '".$this->configuration['pid']."'";

        $GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;

        $this->table_records = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
            $selectFields,
            $fromTable,
            $whereStatement
        );
    }

    /**
     * Insert record into the target table which you have specified in the target section of the strategy
     *
     * @param array $entry
     *
     * @return void
     */
    public function insertRecord(array $entry)
    {
        $field_values       = array();
        $into_table         = $this->configuration['target_table'];

        foreach ($this->configuration["mapping"] as $key => $value) {
            $field_values[$value] = $entry[$key];
        }

        $field_values['pid']      = $this->configuration['pid'];
        $field_values['tstamp']   = time();
        $field_values['crdate']   = time();

        $GLOBALS['TYPO3_DB']->exec_INSERTquery($into_table, $field_values);
        $GLOBALS['TYPO3_DB']->sql_insert_id();
    }

     /**
     * Update a record in the target table which you have specified in the
     * target section of the strategy (don't update the password)
     *
     * @param array $entry
     *
     * @return void
     */
    public function updateRecord(array $entry)
    {
        $into_table       = $this->configuration['target_table'];
        $whereStatement   = "pid = '".$this->configuration['pid']."' AND username = '".$entry[0]."'";
                           
        $field_values     = array();
        $tmp_arr          = array();

        foreach ($this->configuration["mapping"] as $key => $value) {
            $tmp_arr[$value] = $entry[$key];
        }

        $field_values = $this->duplicateArray($tmp_arr, $this->configuration['exclude_from_update']);
        $field_values['tstamp'] = time();
        
        $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery($into_table, $whereStatement, $field_values);
        $cnt = $GLOBALS['TYPO3_DB']->sql_affected_rows();
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
    public function duplicateArray(array $arr, array $exclude_arr = null)
    {
        $exclude = false;

        if ($exclude_arr != null) {
            $exclude_max = count($exclude_arr);
            if (count($exclude_arr) > 0) {
                $exclude = true;
            }

            foreach ($arr as $parentkey => $parentvalue) {
                $chk = $exclude_max;

                if ($exclude) {
                    foreach ($exclude_arr as $key => $value) {
                        if ($value == $parentkey) {
                            unset($arr[$parentkey]);
                        }
                    }
                }
            }
        }

        return $arr;
    }

    public function end()
    {
    }
}
