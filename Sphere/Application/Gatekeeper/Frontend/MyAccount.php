<?php
namespace KREDA\Sphere\Application\Gatekeeper\Frontend;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CertificateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RepeatIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\PasswordField;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutAddress;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableBody;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableColumn;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableHead;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableRow;
use KREDA\Sphere\Client\Frontend\Table\Type\Table;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class MyAccount
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Frontend
 */
class MyAccount extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageStatus()
    {

        $View = new Stage();
        $View->setTitle( 'Mein Account' );
        $View->setDescription( 'Zusammenfassung' );
        $tblAccount = Gatekeeper::serviceAccount()->entityAccountBySession();
        if ($tblAccount) {
            $tblPerson = $tblAccount->getServiceManagementPerson();
            $tblConsumer = $tblAccount->getServiceGatekeeperConsumer();
            $tblAddress = $tblConsumer ? $tblConsumer->getServiceManagementAddress() : false;
        } else {
            $tblAccount = false;
            $tblPerson = false;
            $tblConsumer = false;
            $tblAddress = false;
        }
        $View->setContent(
            ( $tblAccount ? new Table(
                new TableHead(
                    new TableRow(
                        new TableColumn( 'Account', 2 )
                    )
                ), new TableBody( array(
                new TableRow( array(
                    new TableColumn( 'Benutzername (Account)', 1, '20%' ),
                    new TableColumn( $tblAccount->getUsername() )
                ) ),
                new TableRow( array(
                    new TableColumn( 'Zugangstyp (Authentifizierung)' ),
                    new TableColumn( $tblAccount->getTblAccountType()->getName() )
                ) ),
                new TableRow( array(
                    new TableColumn( 'Berechtigungsstufe (Rolle)' ),
                    new TableColumn( $tblAccount->getTblAccountRole()->getName() )
                ) )
            ) ) )
                : new Danger( 'Keine Accountdaten verfügbar', new WarningIcon() ) )
            .( $tblPerson ? new Table(
                new TableHead(
                    new TableRow(
                        new TableColumn( 'Benutzerdaten', 2 )
                    )
                ), new TableBody( array(
                    new TableRow( array(
                        new TableColumn( 'Name', 1, '20%' ),
                        new TableColumn(
                            $tblPerson->getSalutation()
                            .'<br/>'.$tblPerson->getFirstName()
                            .' '.$tblPerson->getMiddleName()
                            .', '.$tblPerson->getLastName()
                        )
                    ) ),
                    new TableRow( array(
                        new TableColumn( 'Geschlecht' ),
                        new TableColumn( $tblPerson->getGender() )
                    ) ),
                    new TableRow( array(
                        new TableColumn( 'Geburtstag' ),
                        new TableColumn( $tblPerson->getBirthday() )
                    ) )
                ) )
            ) : new Warning( 'Keine Personendaten verfügbar', new WarningIcon() ) )
            .( $tblConsumer ? new Table(
                new TableHead(
                    new TableRow(
                        new TableColumn( 'Mandant', 2 )
                    )
                ), new TableBody( array(
                    new TableRow( array(
                        new TableColumn( 'Name', 1, '20%' ),
                        new TableColumn( $tblConsumer->getName() )
                    ) ),
                    new TableRow( array(
                        new TableColumn( 'Kürzel' ),
                        new TableColumn( $tblConsumer->getDatabaseSuffix() )
                    ) ),
                    new TableRow( array(
                        new TableColumn( 'Addresse' ),
                        new TableColumn( $tblAddress ? new LayoutAddress( $tblAddress ) : new Warning( 'Keine Adressdaten verfügbar',
                            new WarningIcon() ) )
                    ) )
                ) )
            ) : new Warning( 'Keine Mandantendaten verfügbar', new WarningIcon() ) )
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
        $View->setContent( Gatekeeper::serviceAccount()->executeChangePassword(
            new Form(
                new FormGroup(
                    new FormRow( array(
                        new FormColumn(
                            new PasswordField( 'CredentialLock', 'Neues Passwort', 'Neues Passwort', new LockIcon() )
                            , 6 ),
                        new FormColumn(
                            new PasswordField( 'CredentialLockSafety', 'Passwort wiederholen', 'Passwort wiederholen',
                                new RepeatIcon() )
                            , 6 )
                    ) )
                ), new SubmitPrimary( 'Neues Passwort speichern' )
            ), $CredentialLock, $CredentialLockSafety
        ) );
        return $View;
    }

    /**
     * @param int $serviceGatekeeperConsumer
     *
     * @return Stage
     */
    public static function stageChangeConsumer( $serviceGatekeeperConsumer )
    {

        $tblConsumerList = Gatekeeper::serviceConsumer()->entityConsumerAll();
        $tblConsumerSelect = array();
        /** @var TblConsumer $tblConsumer */
        foreach ($tblConsumerList as $tblConsumer) {
            $tblConsumerSelect[$tblConsumer->getId()] = $tblConsumer->getDatabaseSuffix().' # '.$tblConsumer->getName();
        }

        /**
         * Form warm
         */
        $SelectConsumer = new SelectBox( 'serviceGatekeeperConsumer', 'Mandant', $tblConsumerSelect,
            new CertificateIcon() );
        if (null === $serviceGatekeeperConsumer) {
            $SelectConsumer->setDefaultValue( Gatekeeper::serviceConsumer()->entityConsumerBySession()->getId() );
        }

        $View = new Stage();
        $View->setTitle( 'Mein Account' );
        $View->setDescription( 'Mandant ändern' );
        $View->setContent( Gatekeeper::serviceAccount()->executeChangeConsumer(
            new Form(
                new FormGroup(
                    new FormRow( array(
                        new FormColumn( $SelectConsumer )
                    ) )
                ), new SubmitPrimary( 'Neuen Mandant speichern' )
            ), $serviceGatekeeperConsumer
        ) );
        return $View;
    }
}
