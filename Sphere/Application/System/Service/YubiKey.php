<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\Assistance\Service\Youtrack;
use KREDA\Sphere\Application\Gatekeeper\Service\Access;
use KREDA\Sphere\Application\Service;
use KREDA\Sphere\Application\System\Service\YubiKey\Certification;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;

/**
 * Class YubiKey
 *
 * @package KREDA\Sphere\Application\System\Service
 */
class YubiKey extends Service
{

    /**
     * @return Landing
     */
    public function apiYubiKey()
    {

        $View = new Landing();
        $View->setTitle( 'YubiKey' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    /**
     * @param null $CredentialKey
     *
     * @throws \Exception
     * @return Landing
     */
    public function apiYubiKeyCertification( $CredentialKey = null )
    {

        $View = new Stage();
        $View->setTitle( 'YubiKey' );
        $View->setDescription( 'Zertifizierung' );
        $View->setMessage( '' );

        $Certification = new Certification();
        $Error = $this->checkFormYubiKey( $Certification, $CredentialKey );
        if ($Error) {
            $View->setContent( $Certification );
            return $View;
        } else {
            if (null !== $CredentialKey) {
                try {
                    if (Access::getApi()->apiValidateYubiKey( $CredentialKey )) {

                    }
                } catch( Access\YubiKey\Exception\Repository\BadOTPException $E ) {
                    $Certification->setErrorWrongKey();
                    $View->setContent( $Certification );
                    return $View;
                } catch( Access\YubiKey\Exception\Repository\ReplayedOTPException $E ) {
                    $Certification->setErrorReplayedKey();
                    $View->setContent( $Certification );
                    return $View;
                } catch( Access\YubiKey\Exception\ComponentException $E ) {

                    throw new \Exception( 'Es ist ein Fehler bei der Anmeldung aufgetreten' );
                }
            }

            $View->setContent( $Certification );
            return $View;
        }
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
