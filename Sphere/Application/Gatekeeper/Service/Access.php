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
    }

    public function setupSystem()
    {

        $this->schemaCreateAccessRight( '' );

        $this->schemaCreateAccount( 'Root', 'OvdZ2üA!Lz{AFÖFp' );

    }

    /**
     * @return bool|integer
     */
    public function apiGetAccountIdBySession()
    {

        /*
                if( false != ( $tblAccount = $this->schemaGetAccountIdBySession() ) ) {
                    return $tblAccount;
                } else {
                    return false;
                }
        */
        if (isset( $_SESSION['Gatekeeper-Valid'] )) {
            return $_SESSION['Gatekeeper-Valid'];
        } else {
            return false;
        }
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

    public function apiValidateCredentials( $CredentialUser, $CredentialLock )
    {

    }

}
