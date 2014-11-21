<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema;

/**
 * Class Access
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service
 */
class Access extends Schema
{

    /**
     *
     */
    public function __construct()
    {

        $this->connectDatabase( 'Access' );
        parent::__construct();
    }

    public function setupSystem()
    {

        //$this->schemaCreateAccessRight( '' );

        $this->schemaCreateAccount( 'Root', 'OvdZ2üA!Lz{AFÖFp' );

    }

    /**
     * @return bool|integer
     */
    public function apiGetAccountIdBySession()
    {

        if (false != ( $tblAccount = $this->schemaGetAccountIdBySession() )) {
            return $tblAccount;
        } else {
            return false;
        }
    }

    /**
     * @param integer $tblAccount
     *
     * @return bool
     */
    public function apiSignIn( $tblAccount )
    {

        session_regenerate_id();
        return $this->schemaCreateSession( session_id(), $tblAccount );
    }

    /**
     * @param string $Value
     *
     * @return bool
     * @throws \Exception
     */
    public function apiValidateYubiKey( $Value )
    {

        $YubiKey = new Access\YubiKey\YubiKey( 19180, 'YJwU33GNiRiw1dE8/MfIMNm8w3Y=' );
        return $YubiKey->verifyKey(
            $YubiKey->parseKey( $Value )
        );
    }

    /**
     * @param string $CredentialUser
     * @param string $CredentialLock
     *
     * @return bool|int
     */
    public function apiValidateCredentials( $CredentialUser, $CredentialLock )
    {

        return $this->schemaGetAccountIdByCredential( $CredentialUser, $CredentialLock );
    }

}
