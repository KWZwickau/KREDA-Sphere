<?php
namespace KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\YubiKeyIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Form\Element\InputPassword;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;

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
        $View->setContent( Gatekeeper::serviceAccount()->executeActionSignInWithToken(
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
                ), new SubmitPrimary( 'Anmelden' )
            ),
            $CredentialName, $CredentialLock, $CredentialKey,
            Gatekeeper::serviceAccount()->entityAccountTypeByName( 'Lehrer' )
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
        $View->setContent( Gatekeeper::serviceAccount()->executeActionSignInWithToken(
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
                ), new SubmitPrimary( 'Anmelden' )
            ),
            $CredentialName, $CredentialLock, $CredentialKey,
            Gatekeeper::serviceAccount()->entityAccountTypeByName( 'System' )
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
        $View->setContent(
            Gatekeeper::serviceAccount()->executeActionSignIn(

                new FormDefault(
                    new GridFormGroup( array(
                            new GridFormRow(
                                new GridFormCol( new InputText( 'CredentialName', 'Benutzername', 'Benutzername',
                                    new PersonIcon() ) )
                            ),
                            new GridFormRow(
                                new GridFormCol( new InputPassword( 'CredentialLock', 'Passwort', 'Passwort',
                                    new LockIcon() ) )
                            )
                        )
                    ), new SubmitPrimary( 'Anmelden' )
                ),

                $CredentialName, $CredentialLock,

                Gatekeeper::serviceAccount()->entityAccountTypeByName( 'Schüler' )

            )

        );
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
        $View->setContent( Gatekeeper::serviceAccount()->executeActionSignInWithToken(
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
                ), new SubmitPrimary( 'Anmelden' )
            ),
            $CredentialName, $CredentialLock, $CredentialKey,
            Gatekeeper::serviceAccount()->entityAccountTypeByName( 'Verwaltung' )
        ) );
        return $View;
    }
}
