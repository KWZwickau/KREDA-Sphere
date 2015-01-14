<?php
namespace KREDA\Sphere\Application\Gatekeeper\Frontend\MyAccount;

use KREDA\Sphere\Application\Gatekeeper\Frontend\MyAccount\Summary\Account;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RepeatIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\AbstractFrontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\AbstractFrontend\Form\Element\InputPassword;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridCol;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridGroup;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridRow;

/**
 * Class MyAccount
 *
 * @package KREDA\Sphere\Application\Gatekeeper\MyAccount
 */
class MyAccount extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function landingSummary()
    {

        $View = new Landing();
        $View->setTitle( 'Mein Account' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        $View->setContent(
            new Account( Gatekeeper::serviceAccount()->entityAccountBySession() )
        );
        return $View;
    }

    /**
     * @param string $CredentialLock
     * @param string $CredentialLockSafety
     *
     * @return Stage
     */
    public static function stageChangePassword( $CredentialLock, $CredentialLockSafety )
    {

        $View = new Stage();
        $View->setTitle( 'Mein Account' );
        $View->setDescription( 'Passwort ändern' );
        $View->setMessage( '' );
        //$View->setMessage( 'Bitte legen Sie ein neues Password fest' );
        $View->setContent( Gatekeeper::serviceAccount()->executeChangePassword(
            new FormDefault(
                new GridGroup(
                    new GridRow( array(
                        new GridCol(
                            new InputPassword( 'CredentialLock', 'Neues Passwort', 'Neues Passwort', new LockIcon() )
                        , 6 ),
                        new GridCol(
                            new InputPassword( 'CredentialLockSafety', 'Passwort wiederholen', 'Passwort wiederholen', new RepeatIcon() )
                        , 6 )
                    ) )
                ), new ButtonSubmitPrimary('Neues Passwort speichern' )
            ), $CredentialLock, $CredentialLockSafety
        ) );
        return $View;
    }
}
