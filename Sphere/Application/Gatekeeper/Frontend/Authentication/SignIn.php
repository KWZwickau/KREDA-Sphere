<?php
namespace KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\YubiKeyIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\AbstractFrontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\AbstractFrontend\Form\Element\InputPassword;
use KREDA\Sphere\Common\AbstractFrontend\Form\Element\InputText;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormRow;

/**
 * Class SignIn
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication
 */
class SignIn extends AbstractFrontend
{

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @return Stage
     */
    public static function stageTeacher( $CredentialName, $CredentialLock, $CredentialKey )
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
    public static function stageSystem( $CredentialName, $CredentialLock, $CredentialKey )
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
    public static function stageStudent( $CredentialName, $CredentialLock )
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
    public static function stageManagement( $CredentialName, $CredentialLock, $CredentialKey )
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
}
