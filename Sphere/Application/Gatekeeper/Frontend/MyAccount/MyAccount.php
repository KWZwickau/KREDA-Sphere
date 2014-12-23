<?php
namespace KREDA\Sphere\Application\Gatekeeper\Frontend\MyAccount;

use KREDA\Sphere\Application\Gatekeeper\Frontend\MyAccount\Consumer\ChangeConsumer;
use KREDA\Sphere\Application\Gatekeeper\Frontend\MyAccount\Password\ChangePassword;
use KREDA\Sphere\Application\Gatekeeper\Frontend\MyAccount\Summary\Account;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Common\AbstractFrontend;

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
        $View->setMessage( 'Bitte legen Sie ein neues Password fest' );
        $View->setContent( Gatekeeper::serviceAccount()->executeChangePassword(
            new ChangePassword(), $CredentialLock, $CredentialLockSafety
        ) );
        return $View;
    }

    /**
     * @param integer $tblConsumer
     *
     * @return Stage
     */
    public static function stageChangeConsumer( $tblConsumer )
    {

        $View = new Stage();
        $View->setTitle( 'Mein Account' );
        $View->setDescription( 'Mandant ändern' );
        $View->setMessage( 'Bitte wählen Sie einen Mandanten' );
        $View->setContent( Gatekeeper::serviceAccount()->executeChangeConsumer(
            new ChangeConsumer(), $tblConsumer
        ) );
        return $View;
    }
}
