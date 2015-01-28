<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware\YubiKey\Exception\ComponentException;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware\YubiKey\Exception\Repository\BadOTPException;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware\YubiKey\Exception\Repository\ReplayedOTPException;
use KREDA\Sphere\Application\System\Service\Token\Certification;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class Token
 *
 * @package KREDA\Sphere\Application\System\Service
 */
class Token extends AbstractService
{

    /**
     * @return Landing
     */
    public function apiToken()
    {

        $View = new Landing();
        $View->setTitle( 'Hardware-Schlüssel' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    /**
     * @param null $CredentialKey
     *
     * @throws \Exception
     * @return Landing
     */
    public function apiTokenCertification( $CredentialKey = null )
    {

        $View = new Stage();
        $View->setTitle( 'Hardware-Schlüssel' );
        $View->setDescription( 'Zertifizierung' );
        $View->setMessage( '' );

        $Certification = new Certification();

        $Error = $this->checkFormYubiKey( $Certification, $CredentialKey );
        if ($Error) {
            $View->setContent( $Certification );
        } else {
            if (null !== $CredentialKey) {
                try {
                    Gatekeeper::serviceToken()->executeCreateToken( $CredentialKey );
                } catch( BadOTPException $E ) {
                    $Certification->setErrorWrongKey();
                    $View->setContent( $Certification );
                    return $View;
                } catch( ReplayedOTPException $E ) {
                    $Certification->setErrorReplayedKey();
                    $View->setContent( $Certification );
                    return $View;
                } catch( ComponentException $E ) {

                    throw new \Exception( 'Es ist ein Fehler bei der Anmeldung aufgetreten' );
                }
            }

            $View->setContent( $Certification );
        }

        $TokenList = Gatekeeper::serviceToken()->entityTokenAll();
        if (false === $TokenList) {
            $TokenList = array();
        } else {
            array_walk( $TokenList, create_function( '&$V', '$V = $V->__toArray();' ) );
        }
        $Certification->setTokenList( $TokenList );

        return $View;
    }

    /**
     * @param Certification $View
     * @param null|string   $CredentialKey
     *
     * @return bool
     */
    private function checkFormYubiKey( Certification &$View, $CredentialKey )
    {

        $Error = false;
        if (null !== $CredentialKey && empty( $CredentialKey )) {
            $View->setErrorEmptyKey();
            $Error = true;
        }
        return $Error;
    }
}
