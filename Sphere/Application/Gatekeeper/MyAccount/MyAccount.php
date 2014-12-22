<?php
namespace KREDA\Sphere\Application\Gatekeeper\MyAccount;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\MyAccount\Consumer\ChangeConsumer;
use KREDA\Sphere\Application\Gatekeeper\MyAccount\Password\ChangePassword;
use KREDA\Sphere\Application\Gatekeeper\MyAccount\Summary\Account;
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
    public static function guiSummary()
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
    public static function guiChangePassword( $CredentialLock, $CredentialLockSafety )
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
    public static function guiChangeConsumer( $tblConsumer )
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
