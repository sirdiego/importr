<?php

declare(strict_types=1);
namespace HDNET\Importr\Service;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Saltedpasswords\Salt\SaltFactory;

/**
 * Class PasswordHashService.
 */
class PasswordHashService
{
    /**
     * This function takes a password as argument, salts it and returns the new password.
     *
     * @param string $password
     *
     * @return string
     */
    public function hash($password)
    {
        if (ExtensionManagementUtility::isLoaded('saltedpasswords')) {
            $salter = SaltFactory::getSaltingInstance(null, 'FE');
            $password = $salter->getHashedPassword($password);

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
        return \preg_match('/^[a-f0-9]{32}$/', $md5);
    }
}
