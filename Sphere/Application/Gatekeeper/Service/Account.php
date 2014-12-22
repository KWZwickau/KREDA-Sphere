<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Authentication\Common\Error;
use KREDA\Sphere\Application\Gatekeeper\Authentication\Common\Redirect;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountTyp;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\EntityAction;

/**
 * Class Account
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service
 */
class Account extends EntityAction
{

    const API_SIGN_IN_ERROR_CREDENTIAL = 21;
    const API_SIGN_IN_ERROR_TOKEN = 22;
    const API_SIGN_IN_ERROR = 23;
    const API_SIGN_IN_SUCCESS = 11;
    private static $ValidSessionCache = false;

    /**
     * @throws \Exception
     */
    public function __construct()
    {

        $this->setDatabaseHandler( 'Gatekeeper', 'Account' );
    }

    public function setupDatabaseContent()
    {

        /**
         * Create SystemAdmin (Root)
         */
        $tblAccountRole = $this->actionCreateAccountRole( 'Root' );
        $tblAccountTyp = $this->actionCreateAccountTyp( 'Root' );
        $this->actionCreateAccount( 'Root', 'OvdZ2üA!Lz{AFÖFp', $tblAccountTyp, $tblAccountRole, null, null, null );

        $this->actionCreateAccountTyp( 'Schüler' );

        $tblAccountRole = $this->actionCreateAccountRole( 'Lehrkraft' );
        $tblAccountTyp = $this->actionCreateAccountTyp( 'Lehrer' );
        $this->actionCreateAccount( 'Schubert', 'Micha', $tblAccountTyp, $tblAccountRole, null, null, null );

        $this->actionCreateAccountTyp( 'Verwaltung' );
    }

    /**
     * @return void
     */
    public function executeSignOut()
    {

        $this->actionDestroySession();
        session_regenerate_id();
    }

    /**
     * @param Error         $View
     * @param string        $CredentialName
     * @param string        $CredentialLock
     * @param string        $CredentialKey
     * @param TblAccountTyp $tblAccountTyp
     *
     * @return Error|Redirect
     */
    public function executeSignInWithToken(
        Error &$View,
        $CredentialName,
        $CredentialLock,
        $CredentialKey,
        TblAccountTyp $tblAccountTyp
    ) {

        switch ($this->checkIsValidCredential( $CredentialName, $CredentialLock, $CredentialKey, $tblAccountTyp )) {
            case Account::API_SIGN_IN_ERROR_CREDENTIAL:
            case Account::API_SIGN_IN_ERROR: {
                if (null !== $CredentialName && empty( $CredentialName )) {
                    $View->setErrorEmptyName();
                }
                if (null !== $CredentialName && !empty( $CredentialName )) {
                    $View->setErrorWrongName();
                }
                if (null !== $CredentialLock && empty( $CredentialLock )) {
                    $View->setErrorEmptyLock();
                }
                if (null !== $CredentialLock && !empty( $CredentialLock )) {
                    $View->setErrorWrongLock();
                }
                break;
            }
            case Account::API_SIGN_IN_ERROR_TOKEN: {
                $View->setErrorWrongKey();
                break;
            }
            case Account::API_SIGN_IN_SUCCESS: {
                return new Redirect( '/Sphere', 1 );
                break;
            }
        }
        return $View;
    }

    /**
     * @param string        $Username
     * @param string        $Password
     * @param bool          $TokenString
     * @param TblAccountTyp $tblAccountTyp
     *
     * @return int
     */
    public function checkIsValidCredential( $Username, $Password, $TokenString, TblAccountTyp $tblAccountTyp )
    {

        if (false === ( $tblAccount = $this->entityAccountByCredential( $Username, $Password, $tblAccountTyp ) )) {
            return self::API_SIGN_IN_ERROR_CREDENTIAL;
        } else {
            if (false === $TokenString) {
                session_regenerate_id();
                $this->actionCreateSession( $tblAccount, session_id() );
                return self::API_SIGN_IN_SUCCESS;
            } else {
                try {
                    if (Gatekeeper::serviceToken()->apiValidateToken( $TokenString )) {
                        if (false === ( $Token = $tblAccount->getServiceGatekeeperToken() )) {
                            return self::API_SIGN_IN_ERROR_TOKEN;
                        } else {
                            if ($Token->getIdentifier() == substr( $TokenString, 0, 12 )) {
                                session_regenerate_id();
                                Gatekeeper::serviceAccount()->actionCreateSession( $tblAccount, session_id() );
                                return self::API_SIGN_IN_SUCCESS;
                            } else {
                                return self::API_SIGN_IN_ERROR_TOKEN;
                            }
                        }
                    } else {
                        return self::API_SIGN_IN_ERROR_TOKEN;
                    }
                } catch( \Exception $E ) {
                    return self::API_SIGN_IN_ERROR_TOKEN;
                }
            }
        }
    }

    /**
     * @param Error         $View
     * @param string        $CredentialName
     * @param string        $CredentialLock
     * @param TblAccountTyp $tblAccountTyp
     *
     * @return Error|Redirect
     */
    public function executeSignIn( Error &$View, $CredentialName, $CredentialLock, TblAccountTyp $tblAccountTyp )
    {

        switch ($this->checkIsValidCredential( $CredentialName, $CredentialLock, false, $tblAccountTyp )) {
            case Account::API_SIGN_IN_ERROR_CREDENTIAL:
            case Account::API_SIGN_IN_ERROR: {
                if (null !== $CredentialName && empty( $CredentialName )) {
                    $View->setErrorEmptyName();
                }
                if (null !== $CredentialName && !empty( $CredentialName )) {
                    $View->setErrorWrongName();
                }
                if (null !== $CredentialLock && empty( $CredentialLock )) {
                    $View->setErrorEmptyLock();
                }
                if (null !== $CredentialLock && !empty( $CredentialLock )) {
                    $View->setErrorWrongLock();
                }
                break;
            }
            case Account::API_SIGN_IN_SUCCESS: {
                return new Redirect( '/Sphere', 1 );
                break;
            }
        }
        return $View;
    }

    /**
     * @param Error   $View
     * @param integer $tblConsumer
     *
     * @return Error
     */
    public function executeChangeConsumer( Error &$View, $tblConsumer )
    {

        if (null !== $tblConsumer && empty( $tblConsumer )) {

        }
        if (!empty( $tblConsumer ) && is_numeric( $tblConsumer )) {
            // TODO: Change Consumer
            return new Redirect( '/Sphere/Gatekeeper/MyAccount', 1 );
        }
        return $View;
    }

    /**
     * @param Error  $View
     * @param string $CredentialLock
     * @param string $CredentialLockSafety
     *
     * @return Error
     */
    public function executeChangePassword( Error &$View, $CredentialLock, $CredentialLockSafety )
    {

        if (null !== $CredentialLock && empty( $CredentialLock )) {
            $View->setErrorEmptyLock();
        }
        if (null !== $CredentialLockSafety && empty( $CredentialLockSafety )) {
            $View->setErrorEmptyLockSafety();
        }
        if (!empty( $CredentialLock ) && !empty( $CredentialLockSafety )) {

            if ($CredentialLock == $CredentialLockSafety) {
                $this->actionChangePassword( $CredentialLock );
                return new Redirect( '/Sphere/Gatekeeper/MyAccount', 1 );
            } else {
                $View->setErrorWrongLockSafety();
            }
        }

        return $View;
    }

    /**
     * @return bool
     */
    public function checkIsValidSession()
    {

        if (self::$ValidSessionCache) {
            return true;
        }

        if (false === $this->entityAccountBySession()) {
            self::$ValidSessionCache = false;
        } else {
            self::$ValidSessionCache = true;
        }
        return self::$ValidSessionCache;
    }

    /**
     * @param null|string $Session
     *
     * @return bool|TblAccount
     */
    public function entityAccountBySession( $Session = null )
    {

        return parent::entityAccountBySession( $Session );
    }

    /**
     * @return Table
     */
    public function schemaTableAccount()
    {

        return $this->getTableAccount();
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccount
     */
    public function entityAccountById( $Id )
    {

        return parent::entityAccountById( $Id );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccountTyp
     */
    public function entityAccountTypById( $Id )
    {

        return parent::entityAccountTypById( $Id );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblAccountTyp
     */
    public function entityAccountTypByName( $Name )
    {

        return parent::entityAccountTypByName( $Name );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccountRole
     */
    public function entityAccountRoleById( $Id )
    {

        return parent::entityAccountRoleById( $Id );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblAccount
     */
    public function entityAccountByUsername( $Name )
    {

        return parent::entityAccountByUsername( $Name );
    }
}
