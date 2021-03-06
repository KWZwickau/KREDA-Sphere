<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountAccessList;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountSession;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountType;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\EntityAction;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Common\Wire\Data;
use KREDA\Sphere\Common\Wire\Effect;

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
    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;
    /** @var null|bool $ValidSessionCache */
    private static $ValidSessionCache = null;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Gatekeeper', 'Account' );
    }

    public function setupDatabaseContent()
    {

        /**
         * Create System-Admin
         */
        $tblAccountRole = $this->actionCreateAccountRole( 'System' );
        $tblAccountType = $this->actionCreateAccountType( 'System' );
        $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerBySuffix( 'DS' );
        $this->actionCreateAccount( 'System', 'System',
            $tblAccountType, $tblAccountRole, null, null, $tblConsumer
        );

        $this->actionAddRoleAccess( $tblAccountRole,
            Gatekeeper::serviceAccess()->entityAccessByName( 'System:Administrator' )
        );
        $this->actionAddRoleAccess( $tblAccountRole,
            Gatekeeper::serviceAccess()->entityAccessByName( 'Gatekeeper:Administrator' )
        );
        $this->actionAddRoleAccess( $tblAccountRole,
            Gatekeeper::serviceAccess()->entityAccessByName( 'Management:Administrator' )
        );
        $this->actionAddRoleAccess( $tblAccountRole,
            Gatekeeper::serviceAccess()->entityAccessByName( 'Graduation:Administrator' )
        );
        $this->actionAddRoleAccess( $tblAccountRole,
            Gatekeeper::serviceAccess()->entityAccessByName( 'Billing:Administrator' )
        );

        /**
         * Create Consumer-Admin
         */
        $tblAccountRole = $this->actionCreateAccountRole( 'Administrator' );
        $this->actionAddRoleAccess( $tblAccountRole,
            Gatekeeper::serviceAccess()->entityAccessByName( 'Gatekeeper:MyAccount' )
        );
        $this->actionAddRoleAccess( $tblAccountRole,
            Gatekeeper::serviceAccess()->entityAccessByName( 'Management:Administrator' )
        );
//        $this->actionAddRoleAccess( $tblAccountRole,
//            Gatekeeper::serviceAccess()->entityAccessByName( 'Graduation:Administrator' )
//        );
//        $this->actionAddRoleAccess( $tblAccountRole,
//            Gatekeeper::serviceAccess()->entityAccessByName( 'Billing:Administrator' )
//        );

        /**
         * Create Primary Login-Type
         */
        $this->actionCreateAccountType( 'Verwaltung' );
        $this->actionCreateAccountType( 'Lehrer' );
        $this->actionCreateAccountType( 'Schüler' );
    }

    /**
     * @return Redirect
     */
    public function executeActionSignOut()
    {

        $this->actionDestroySession();
        if (!headers_sent()) {
            session_regenerate_id();
        }

        return new Redirect( '/Sphere/Gatekeeper/SignIn', 0 );
    }

    /**
     * @param AbstractType $View
     * @param string         $CredentialName
     * @param string         $CredentialLock
     * @param string         $CredentialKey
     * @param TblAccountType $tblAccountType
     *
     * @return \KREDA\Sphere\Client\Frontend\Form\AbstractType|\KREDA\Sphere\Client\Frontend\Redirect
     */
    public function executeActionSignInWithToken(
        AbstractType &$View,
        $CredentialName,
        $CredentialLock,
        $CredentialKey,
        TblAccountType $tblAccountType
    ) {

        switch ($this->checkIsValidCredential( $CredentialName, $CredentialLock, $CredentialKey, $tblAccountType )) {
            case Account::API_SIGN_IN_ERROR_CREDENTIAL:
            case Account::API_SIGN_IN_ERROR: {
                if (null !== $CredentialName && empty( $CredentialName )) {
                    $View->setError( 'CredentialName', 'Bitte geben Sie einen gültigen Benutzernamen ein' );
                }
                if (null !== $CredentialName && !empty( $CredentialName )) {
                    $View->setError( 'CredentialName', 'Bitte geben Sie einen gültigen Benutzernamen ein' );
                }
                if (null !== $CredentialLock && empty( $CredentialLock )) {
                    $View->setError( 'CredentialLock', 'Bitte geben Sie ein Passwort ein' );
                }
                if (null !== $CredentialLock && !empty( $CredentialLock )) {
                    $View->setError( 'CredentialLock', 'Bitte geben Sie ein Passwort ein' );
                }
                break;
            }
            case Account::API_SIGN_IN_ERROR_TOKEN: {
                $View->setSuccess( 'CredentialName', '' );
                $View->setSuccess( 'CredentialLock', '' );
                $View->setError( 'CredentialKey', 'Der von Ihnen angegebene YubiKey ist nicht gültig.'
                    .'<br/>Bitte verwenden Sie Ihren YubiKey um dieses Feld zu befüllen' );
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
     * @param string         $Username
     * @param string         $Password
     * @param bool           $TokenString
     * @param TblAccountType $tblAccountType
     *
     * @return int
     */
    private function checkIsValidCredential( $Username, $Password, $TokenString, TblAccountType $tblAccountType )
    {

        if (false === ( $tblAccount = $this->entityAccountByCredential( $Username, $Password, $tblAccountType ) )) {
            return self::API_SIGN_IN_ERROR_CREDENTIAL;
        } else {
            if (false === $TokenString) {
                session_regenerate_id();
                $this->actionCreateSession( $tblAccount, session_id() );
                return self::API_SIGN_IN_SUCCESS;
            } else {
                try {
                    if (Gatekeeper::serviceToken()->checkIsValidToken( $TokenString )) {
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
     * @param AbstractType $View
     * @param string         $CredentialName
     * @param string         $CredentialLock
     * @param TblAccountType $tblAccountType
     *
     * @return AbstractType|\KREDA\Sphere\Client\Frontend\Redirect
     */
    public function executeActionSignIn(
        AbstractType &$View,
        $CredentialName,
        $CredentialLock,
        TblAccountType $tblAccountType
    ) {

        switch ($this->checkIsValidCredential( $CredentialName, $CredentialLock, false, $tblAccountType )) {
            case self::API_SIGN_IN_ERROR_CREDENTIAL:
            case self::API_SIGN_IN_ERROR: {
                if (null !== $CredentialName && empty( $CredentialName )) {
                    $View->setError( 'CredentialName', 'Bitte geben Sie einen gültigen Benutzernamen ein' );
                }
                if (null !== $CredentialName && !empty( $CredentialName )) {
                    $View->setError( 'CredentialName', 'Bitte geben Sie einen gültigen Benutzernamen ein' );
                }
                if (null !== $CredentialLock && empty( $CredentialLock )) {
                    $View->setError( 'CredentialLock', 'Bitte geben Sie ein Passwort ein' );
                }
                if (null !== $CredentialLock && !empty( $CredentialLock )) {
                    $View->setError( 'CredentialLock', 'Bitte geben Sie ein Passwort ein' );
                }
                break;
            }
            case self::API_SIGN_IN_SUCCESS: {
                return new Redirect( '/Sphere', 0 );
                break;
            }
        }
        return $View;
    }

    /**
     * @param AbstractType $View
     * @param integer      $tblConsumer
     *
     * @return \KREDA\Sphere\Client\Frontend\Form\AbstractType|Redirect
     */
    public function executeChangeConsumer( AbstractType &$View, $tblConsumer )
    {

        if (null !== $tblConsumer && empty( $tblConsumer )) {
            $View->setError( 'serviceGatekeeper_Consumer', 'Bitte wählen Sie einen gültigen Mandanten' );
        } else {
            if (!empty( $tblConsumer ) && is_numeric( $tblConsumer )) {
                $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerById( $tblConsumer );
                if (false !== $tblConsumer) {
                    $this->actionChangeConsumer( $tblConsumer );
                    return new Redirect( '/Sphere/Gatekeeper/MyAccount', 0 );
                } else {
                    $View->setError( 'serviceGatekeeper_Consumer', 'Bitte wählen Sie einen gültigen Mandanten' );
                }
            }
        }
        return $View;
    }

    /**
     * @param AbstractType $View
     * @param string       $RoleName
     *
     * @return \KREDA\Sphere\Client\Frontend\Form\AbstractType|Redirect
     */
    public function executeCreateRole( AbstractType &$View, $RoleName )
    {

        if (null !== $RoleName && empty( $RoleName )) {
            $View->setError( 'RoleName', 'Bitte geben Sie einen Namen ein' );
        }
        if (!empty( $RoleName )) {
            $View->setSuccess( 'RoleName', 'Die Rolle wurde hinzugefügt' );
            $this->actionCreateAccountRole( $RoleName );
            return new Redirect( '/Sphere/System/Authorization/Role', 0 );
        }
        return $View;
    }

    /**
     * @param AbstractType $View
     * @param string       $CredentialLock
     * @param string       $CredentialLockSafety
     *
     * @return AbstractType|Redirect
     */
    public function executeChangePassword( AbstractType &$View, $CredentialLock, $CredentialLockSafety )
    {

        if (null !== $CredentialLock && empty( $CredentialLock )) {
            $View->setError( 'CredentialLock', 'Bitte geben Sie ein Passwort ein' );
        }
        if (null !== $CredentialLockSafety && empty( $CredentialLockSafety )) {
            $View->setError( 'CredentialLockSafety', 'Bitte geben Sie das Passwort erneut ein' );
        }
        if (!empty( $CredentialLock ) && !empty( $CredentialLockSafety )) {

            if ($CredentialLock == $CredentialLockSafety) {
                $this->actionChangePassword( $CredentialLock );
                return new Redirect( '/Sphere/Gatekeeper/MyAccount', 1 );
            } else {
                $View->setError( 'CredentialLockSafety', 'Die beiden Passwörter stimmen nicht überein' );
            }
        }

        return $View;
    }

    /**
     * @param TblToken   $tblToken
     * @param TblAccount $tblAccount
     *
     * @return bool
     */
    public function executeChangeToken( TblToken $tblToken, TblAccount $tblAccount = null )
    {

        return parent::actionChangeToken( $tblToken, $tblAccount );
    }

    /**
     * @param TblPerson $tblPerson
     * @param TblAccount $tblAccount
     *
     * @return bool
     */
    public function executeChangePerson( TblPerson $tblPerson, TblAccount $tblAccount = null )
    {

        return parent::actionChangePerson( $tblPerson, $tblAccount );
    }

    /**
     * @param TblAccountRole $TblAccountRole
     * @param TblAccess      $TblAccess
     *
     * @return TblAccountAccessList
     */
    public function executeAddRoleAccess(
        TblAccountRole $TblAccountRole,
        TblAccess $TblAccess
    ) {

        return parent::actionAddRoleAccess( $TblAccountRole, $TblAccess );
    }

    /**
     * @param TblAccountRole $TblAccountRole
     * @param TblAccess      $TblAccess
     *
     * @return bool
     */
    public function executeRemoveRoleAccess(
        TblAccountRole $TblAccountRole,
        TblAccess $TblAccess
    ) {

        return parent::actionRemoveRoleAccess( $TblAccountRole, $TblAccess );
    }

    /**
     * @return bool
     */
    public function checkIsValidSession()
    {

        if (null === self::$ValidSessionCache) {
            if (false === $this->entityAccountBySession()) {
                self::$ValidSessionCache = false;
            } else {
                self::$ValidSessionCache = true;
            }
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
     * @param TblAccount $tblAccount
     *
     * @return bool|TblAccountSession[]
     */
    public function entitySessionAllByAccount( TblAccount $tblAccount )
    {

        return parent::entitySessionAllByAccount( $tblAccount );
    }


    /**
     * @return Table
     */
    public function getTableAccount()
    {

        return parent::getTableAccount();
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
     * @return bool|TblAccountSession
     */
    public function entitySessionById( $Id )
    {

        return parent::entitySessionById( $Id );
    }

    /**
     * @param TblToken $tblToken
     *
     * @return bool|TblAccount[]
     */
    public function entityAccountAllByToken( TblToken $tblToken )
    {

        return parent::entityAccountAllByToken( $tblToken );
    }

    /**
     * @param TblConsumer $tblConsumer
     *
     * @return bool|Account\Entity\TblAccount[]
     */
    public function entityAccountAllByConsumer( TblConsumer $tblConsumer )
    {

        return parent::entityAccountAllByConsumer( $tblConsumer );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblAccountType
     */
    public function entityAccountTypeByName( $Name )
    {

        return parent::entityAccountTypeByName( $Name );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblAccountRole
     */
    public function entityAccountRoleByName( $Name )
    {

        return parent::entityAccountRoleByName( $Name );
    }

    /**
     * @return bool|TblAccountRole[]
     */
    public function entityAccountRoleAll()
    {

        return parent::entityAccountRoleAll();
    }

    /**
     * @param TblAccountRole $tblAccountRole
     *
     * @return bool|TblAccess[]
     */
    public function entityAccessAllByAccountRole( TblAccountRole $tblAccountRole )
    {

        return parent::entityAccessAllByAccountRole( $tblAccountRole );
    }

    /**
     * @return bool|TblAccountType[]
     */
    public function entityAccountTypeAll()
    {

        return parent::entityAccountTypeAll();
    }

    /**
     * @param AbstractType   $View
     * @param string         $Username
     * @param string         $Password
     * @param string         $PasswordSafety
     * @param TblAccountType $tblAccountType
     * @param TblAccountRole $tblAccountRole
     * @param TblConsumer    $tblConsumer
     *
     * @return TblAccount
     */
    public function executeCreateAccount(
        AbstractType &$View,
        $Username,
        $Password,
        $PasswordSafety,
        TblAccountType $tblAccountType,
        TblAccountRole $tblAccountRole,
        TblConsumer $tblConsumer
    ) {

        $Username = trim( $Username );
        $Password = trim( $Password );
        $PasswordSafety = trim( $PasswordSafety );

        $Error = false;

        if (empty( $Username )) {
            $View->setError( 'Account[Name]', 'Bitte geben Sie einen Benutzernamen an' );
            $Error = true;
        } else {
            if (preg_match( '!^[a-z0-9öäüß]+$!is', $Username )) {
                $Username = $tblConsumer->getDatabaseSuffix().'-'.$Username;
                if (!Gatekeeper::serviceAccount()->entityAccountByUsername( $Username )) {
                    $View->setSuccess( 'Account[Name]', '' );
                } else {
                    $View->setError( 'Account[Name]', 'Der angegebene Benutzername ist bereits vergeben' );
                    $Error = true;
                }
            } else {
                $View->setError( 'Account[Name]', 'Der Benutzername darf nur Buchstaben und Zahlen enthalten' );
                $Error = true;
            }
        }
        if (empty( $Password )) {
            $View->setError( 'Account[Password]', 'Bitte geben Sie ein Passwort an' );
            $Error = true;
        } else {
            if (strlen( $Password ) >= 8) {
                $View->setSuccess( 'Account[Password]', '' );
            } else {
                $View->setError( 'Account[Password]', 'Das Passwort muss mindestens 8 Zeichen lang sein' );
                $Error = true;
            }
        }
        if (empty( $PasswordSafety )) {
            $View->setError( 'Account[PasswordSafety]', 'Bitte geben Sie ein Passwort an' );
            $Error = true;
        }
        if ($Password != $PasswordSafety) {
            $View->setError( 'Account[Password]', '' );
            $View->setError( 'Account[PasswordSafety]', 'Die beiden Passworte stimmen nicht überein' );
            $Error = true;
        } else {
            if (!empty( $Password ) && !empty( $PasswordSafety )) {
                $View->setSuccess( 'Account[PasswordSafety]', '' );
            } else {
                $View->setError( 'Account[PasswordSafety]', '' );
            }
        }

        if (!$Error) {
            parent::actionCreateAccount(
                $Username, $Password, $tblAccountType, $tblAccountRole, null, null, $tblConsumer
            );
            return new Redirect( '/Sphere/Management/Account', 0 );
        }

        return $View;
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

    /**
     * @param AbstractType $View
     * @param TblAccount   $tblAccount
     * @param null|array   $Account
     *
     * @return AbstractType|Redirect
     */
    public function executeChangeAccount(
        AbstractType &$View,
        TblAccount $tblAccount,
        $Account
    ) {

        if (null === $Account) {
            return $View;
        }

        $Password = trim( $Account['Password'] );
        $PasswordSafety = trim( $Account['PasswordSafety'] );

        $Error = false;

        if (!empty( $Password ) && !empty( $PasswordSafety )) {
            if (strlen( $Password ) >= 8) {
                $View->setSuccess( 'Account[Password]', '' );
            } else {
                $View->setError( 'Account[Password]', 'Das Passwort muss mindestens 8 Zeichen lang sein' );
                $Error = true;
            }
            if (!empty( $Password ) && empty( $PasswordSafety )) {
                $View->setError( 'Account[PasswordSafety]', 'Bitte geben Sie ein Passwort an' );
                $Error = true;
            }
            if ($Password != $PasswordSafety) {
                $View->setError( 'Account[Password]', '' );
                $View->setError( 'Account[PasswordSafety]', 'Die beiden Passworte stimmen nicht überein' );
                $Error = true;
            } else {
                if (!empty( $Password ) && !empty( $PasswordSafety )) {
                    $View->setSuccess( 'Account[PasswordSafety]', '' );
                } else {
                    $View->setError( 'Account[PasswordSafety]', '' );
                }
            }
        }
        $tblAccountType = Gatekeeper::serviceAccount()->entityAccountTypeById( $Account['Type'] );
        $tblAccountRole = Gatekeeper::serviceAccount()->entityAccountRoleById( $Account['Role'] );
        if ($Account['Token']) {
            $tblToken = Gatekeeper::serviceToken()->entityTokenById( $Account['Token'] );
        } else {
            $tblToken = null;
        }

        if (!$Error) {
            if (!empty( $Password )) {
                parent::actionChangePassword( $Password, $tblAccount );
            }
            parent::actionChangeToken( $tblToken, $tblAccount );
            parent::actionChangeAccountRole( $tblAccountRole, $tblAccount );
            parent::actionChangeAccountType( $tblAccountType, $tblAccount );

            return new Redirect( '/Sphere/Management/Account', 0 );
        }

        return $View;
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccountType
     */
    public function entityAccountTypeById( $Id )
    {

        return parent::entityAccountTypeById( $Id );
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
     * @param TblAccount $tblAccount
     *
     * @return bool|Effect
     */
    public function executeDestroyAccount( TblAccount $tblAccount )
    {

        $Effect = Gatekeeper::observerDestroyAccount()->sendWire( new Data( $tblAccount->getId() ) );
        if (true === $Effect) {
            return $this->actionDestroyAccount( $tblAccount );
        } else {
            return $Effect;
        }
    }

    /**
     * @param TblAccountSession $tblAccountSession
     *
     * @return bool|Effect
     */
    public function executeDestroySession( TblAccountSession $tblAccountSession )
    {

        $Effect = Gatekeeper::observerDestroySession()->sendWire( new Data( $tblAccountSession->getId() ) );
        if (true === $Effect) {
            return $this->actionDestroySession( $tblAccountSession->getSession() );
        } else {
            return $Effect;
        }
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return bool|TblAccount[]
     */
    public function entityAccountAllByPerson( TblPerson $tblPerson )
    {

        return parent::entityAccountAllByPerson( $tblPerson );
    }
}
