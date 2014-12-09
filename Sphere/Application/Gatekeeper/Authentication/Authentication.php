<?php
namespace KREDA\Sphere\Application\Gatekeeper\Authentication;

use KREDA\Sphere\Application\Gatekeeper\Authentication\Common\Redirect;
use KREDA\Sphere\Application\Gatekeeper\Authentication\SignIn\SignInManagement;
use KREDA\Sphere\Application\Gatekeeper\Authentication\SignIn\SignInStudent;
use KREDA\Sphere\Application\Gatekeeper\Authentication\SignIn\SignInSwitch;
use KREDA\Sphere\Application\Gatekeeper\Authentication\SignIn\SignInTeacher;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractModule;

/**
 * Class Authentication
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Authentication
 */
class Authentication extends AbstractModule
{

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @return Stage
     */
    public static function guiSignInTeacher( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $View = new Stage();
        $View->setTitle( 'Anmeldung' );
        $View->setDescription( 'Lehrer' );
        $View->setMessage( 'Bitte geben Sie Ihre Benutzerdaten ein' );
        $View->setContent( Gatekeeper::serviceAccount()->executeSignInWithToken(
            new SignInTeacher(), $CredentialName, $CredentialLock, $CredentialKey
        ) );
        return $View;
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     *
     * @return Stage
     */
    public static function guiSignInStudent( $CredentialName, $CredentialLock )
    {

        $View = new Stage();
        $View->setTitle( 'Anmeldung' );
        $View->setDescription( 'Schüler' );
        $View->setMessage( 'Bitte geben Sie Ihre Benutzerdaten ein' );
        $View->setContent( Gatekeeper::serviceAccount()->executeSignIn(
            new SignInStudent(), $CredentialName, $CredentialLock
        ) );
        return $View;
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @return Stage
     */
    public static function guiSignInManagement( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $View = new Stage();
        $View->setTitle( 'Anmeldung' );
        $View->setDescription( 'Verwaltung' );
        $View->setMessage( 'Bitte geben Sie Ihre Benutzerdaten ein' );
        $View->setContent( Gatekeeper::serviceAccount()->executeSignInWithToken(
            new SignInManagement(), $CredentialName, $CredentialLock, $CredentialKey
        ) );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function guiSignInSwitch()
    {

        $View = new Stage();
        $View->setTitle( 'Anmeldung' );
        $View->setDescription( '' );
        $View->setMessage( 'Bitte wählen Sie den Typ der Anmeldung' );
        $View->setContent( new SignInSwitch() );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function guiSignOut()
    {

        Gatekeeper::serviceAccount()->executeSignOut();
        $View = new Stage();
        $View->setTitle( 'Abmelden' );
        $View->setDescription( '' );
        $View->setMessage( 'Bitte warten...' );
        $View->setContent( new Redirect( '/Sphere/Gatekeeper/SignIn', 1 ) );
        return $View;
    }
}
