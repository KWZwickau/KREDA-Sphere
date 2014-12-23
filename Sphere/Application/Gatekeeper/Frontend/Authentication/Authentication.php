<?php
namespace KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication;

use KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication\SignIn\SignInManagement;
use KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication\SignIn\SignInStudent;
use KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication\SignIn\SignInSwitch;
use KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication\SignIn\SignInTeacher;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Authentication
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication
 */
class Authentication extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageWelcome()
    {

        $View = new Stage();
        $View->setTitle( 'Willkommen' );
        $View->setDescription( 'KREDA Professional' );
        $View->setMessage( date( 'd.m.Y - H:i:s' ) );
        $View->setContent( '' );
        return $View;
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @return Stage
     */
    public static function stageSignInTeacher( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $View = new Stage();
        $View->setTitle( 'Anmeldung' );
        $View->setDescription( 'Lehrer' );
        $View->setMessage( 'Bitte geben Sie Ihre Benutzerdaten ein' );
        $View->setContent( Gatekeeper::serviceAccount()->executeSignInWithToken(
            new SignInTeacher(), $CredentialName, $CredentialLock, $CredentialKey,
            Gatekeeper::serviceAccount()->entityAccountTypByName( 'Lehrer' )
        ) );
        return $View;
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     *
     * @return Stage
     */
    public static function stageSignInStudent( $CredentialName, $CredentialLock )
    {

        $View = new Stage();
        $View->setTitle( 'Anmeldung' );
        $View->setDescription( 'Schüler' );
        $View->setMessage( 'Bitte geben Sie Ihre Benutzerdaten ein' );
        $View->setContent( Gatekeeper::serviceAccount()->executeSignIn(
            new SignInStudent(), $CredentialName, $CredentialLock,
            Gatekeeper::serviceAccount()->entityAccountTypByName( 'Schüler' )
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
    public static function stageSignInManagement( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $View = new Stage();
        $View->setTitle( 'Anmeldung' );
        $View->setDescription( 'Verwaltung' );
        $View->setMessage( 'Bitte geben Sie Ihre Benutzerdaten ein' );
        $View->setContent( Gatekeeper::serviceAccount()->executeSignInWithToken(
            new SignInManagement(), $CredentialName, $CredentialLock, $CredentialKey,
            Gatekeeper::serviceAccount()->entityAccountTypByName( 'Verwaltung' )
        ) );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageSignInSwitch()
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
    public static function stageSignOut()
    {

        Gatekeeper::serviceAccount()->executeSignOut();
        $View = new Stage();
        $View->setTitle( 'Abmelden' );
        $View->setDescription( '' );
        $View->setMessage( 'Bitte warten...' );
        $View->setContent( self::getRedirect( '/Sphere/Gatekeeper/SignIn', 1 ) );
        return $View;
    }
}
