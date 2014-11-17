<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use KREDA\Sphere\Application\Gatekeeper\Service\Access\Setup;

/**
 * Class Access
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service
 */
class Access extends Setup
{

    /**
     *
     */
    public function __construct()
    {

        $this->connectDatabase( 'Access' );
    }

    /**
     * @return bool
     */
    public function apiSessionIsValid()
    {

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
