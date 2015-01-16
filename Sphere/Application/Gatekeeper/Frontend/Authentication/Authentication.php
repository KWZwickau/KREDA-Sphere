<?php
namespace KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\YubiKeyIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\AbstractFrontend\Button\Element\ButtonDangerLink;
use KREDA\Sphere\Common\AbstractFrontend\Button\Element\ButtonPrimaryLink;
use KREDA\Sphere\Common\AbstractFrontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\AbstractFrontend\Button\Structure\GroupDefault;
use KREDA\Sphere\Common\AbstractFrontend\Form\Element\InputPassword;
use KREDA\Sphere\Common\AbstractFrontend\Form\Element\InputText;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormRow;

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
            new FormDefault(
                new GridFormGroup( array(
                        new GridFormRow(
                            new GridFormCol( new InputText( 'CredentialName', 'Benutzername', '', new PersonIcon() ) )
                        ),
                        new GridFormRow(
                            new GridFormCol( new InputPassword( 'CredentialLock', 'Passwort', '', new LockIcon() ) )
                        ),
                        new GridFormRow(
                            new GridFormCol( new InputPassword( 'CredentialKey', 'YubiKey', '', new YubiKeyIcon() ) )
                        )
                    )
                ), new ButtonSubmitPrimary( 'Anmelden' )
            ),
            $CredentialName, $CredentialLock, $CredentialKey,
            Gatekeeper::serviceAccount()->entityAccountTypByName( 'Lehrer' )
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
    public static function stageSignInSystem( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $View = new Stage();
        $View->setTitle( 'Anmeldung' );
        $View->setDescription( 'System' );
        $View->setMessage( 'Bitte geben Sie Ihre Benutzerdaten ein' );
        $View->setContent( Gatekeeper::serviceAccount()->executeSignInWithToken(
            new FormDefault(
                new GridFormGroup( array(
                        new GridFormRow(
                            new GridFormCol( new InputText( 'CredentialName', 'Benutzername', '', new PersonIcon() ) )
                        ),
                        new GridFormRow(
                            new GridFormCol( new InputPassword( 'CredentialLock', 'Passwort', '', new LockIcon() ) )
                        ),
                        new GridFormRow(
                            new GridFormCol( new InputPassword( 'CredentialKey', 'YubiKey', '', new YubiKeyIcon() ) )
                        )
                    )
                ), new ButtonSubmitPrimary( 'Anmelden' )
            ),
            $CredentialName, $CredentialLock, $CredentialKey,
            Gatekeeper::serviceAccount()->entityAccountTypByName( 'System' )
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
            new FormDefault(
                new GridFormGroup( array(
                        new GridFormRow(
                            new GridFormCol( new InputText( 'CredentialName', 'Benutzername', '', new PersonIcon() ) )
                        ),
                        new GridFormRow(
                            new GridFormCol( new InputPassword( 'CredentialLock', 'Passwort', '', new LockIcon() ) )
                        )
                    )
                ), new ButtonSubmitPrimary( 'Anmelden' )
            ),
            $CredentialName, $CredentialLock,
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
            new FormDefault(
                new GridFormGroup( array(
                        new GridFormRow(
                            new GridFormCol( new InputText( 'CredentialName', 'Benutzername', '', new PersonIcon() ) )
                        ),
                        new GridFormRow(
                            new GridFormCol( new InputPassword( 'CredentialLock', 'Passwort', '', new LockIcon() ) )
                        ),
                        new GridFormRow(
                            new GridFormCol( new InputPassword( 'CredentialKey', 'YubiKey', '', new YubiKeyIcon() ) )
                        )
                    )
                ), new ButtonSubmitPrimary( 'Anmelden' )
            ),
            $CredentialName, $CredentialLock, $CredentialKey,
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
        $View->setContent(
        //new SignInSwitch()
            new GroupDefault( array(
                new ButtonPrimaryLink(
                    'Schüler', 'Gatekeeper/SignIn/Student', new LockIcon()
                ),
                new ButtonPrimaryLink(
                    'Lehrer', 'Gatekeeper/SignIn/Teacher', new LockIcon()
                ),
                new ButtonPrimaryLink(
                    'Verwaltung', 'Gatekeeper/SignIn/Management', new LockIcon()
                ),
                new ButtonDangerLink(
                    'System', 'Gatekeeper/SignIn/System', new LockIcon()
                )
            ) )
        );
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
