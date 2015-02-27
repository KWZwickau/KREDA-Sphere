<?php
namespace KREDA\Sphere\Application\Gatekeeper\Frontend;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CertificateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RepeatIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Complex\Structure\ComplexAddress;
use KREDA\Sphere\Common\Frontend\Form\Element\InputPassword;
use KREDA\Sphere\Common\Frontend\Form\Element\InputSelect;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableBody;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableCol;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableHead;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableRow;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableDefault;

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
            ( $tblAccount ? new TableDefault(
                new GridTableHead(
                    new GridTableRow(
                        new GridTableCol( 'Account', 2 )
                    )
                ), new GridTableBody( array(
                new GridTableRow( array(
                    new GridTableCol( 'Benutzername (Account)', 1, '20%' ),
                    new GridTableCol( $tblAccount->getUsername() )
                ) ),
                new GridTableRow( array(
                    new GridTableCol( 'Zugangstyp (Authentifizierung)' ),
                    new GridTableCol( $tblAccount->getTblAccountType()->getName() )
                ) ),
                new GridTableRow( array(
                    new GridTableCol( 'Berechtigungsstufe (Rolle)' ),
                    new GridTableCol( $tblAccount->getTblAccountRole()->getName() )
                ) )
            ) ) )
                : new MessageDanger( 'Keine Accountdaten verfügbar', new WarningIcon() ) )
            .( $tblPerson ? new TableDefault(
                new GridTableHead(
                    new GridTableRow(
                        new GridTableCol( 'Benutzerdaten', 2 )
                    )
                ), new GridTableBody( array(
                    new GridTableRow( array(
                        new GridTableCol( 'Name', 1, '20%' ),
                        new GridTableCol(
                            $tblPerson->getSalutation()
                            .'<br/>'.$tblPerson->getFirstName()
                            .' '.$tblPerson->getMiddleName()
                            .', '.$tblPerson->getLastName()
                        )
                    ) ),
                    new GridTableRow( array(
                        new GridTableCol( 'Geschlecht' ),
                        new GridTableCol( $tblPerson->getGender() )
                    ) ),
                    new GridTableRow( array(
                        new GridTableCol( 'Geburtstag' ),
                        new GridTableCol( $tblPerson->getBirthday() )
                    ) )
                ) )
            ) : new MessageWarning( 'Keine Personendaten verfügbar', new WarningIcon() ) )
            .( $tblConsumer ? new TableDefault(
                new GridTableHead(
                    new GridTableRow(
                        new GridTableCol( 'Mandant', 2 )
                    )
                ), new GridTableBody( array(
                    new GridTableRow( array(
                        new GridTableCol( 'Name', 1, '20%' ),
                        new GridTableCol( $tblConsumer->getName() )
                    ) ),
                    new GridTableRow( array(
                        new GridTableCol( 'Kürzel' ),
                        new GridTableCol( $tblConsumer->getDatabaseSuffix() )
                    ) ),
                    new GridTableRow( array(
                        new GridTableCol( 'Addresse' ),
                        new GridTableCol( $tblAddress ? new ComplexAddress( $tblAddress ) : new MessageWarning( 'Keine Adressdaten verfügbar',
                            new WarningIcon() ) )
                    ) )
                ) )
            ) : new MessageWarning( 'Keine Mandantendaten verfügbar', new WarningIcon() ) )
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
            new FormDefault(
                new GridFormGroup(
                    new GridFormRow( array(
                        new GridFormCol(
                            new InputPassword( 'CredentialLock', 'Neues Passwort', 'Neues Passwort', new LockIcon() )
                            , 6 ),
                        new GridFormCol(
                            new InputPassword( 'CredentialLockSafety', 'Passwort wiederholen', 'Passwort wiederholen',
                                new RepeatIcon() )
                            , 6 )
                    ) )
                ), new ButtonSubmitPrimary( 'Neues Passwort speichern' )
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
        $SelectConsumer = new InputSelect( 'serviceGatekeeperConsumer', 'Mandant', $tblConsumerSelect,
            new CertificateIcon() );
        if (null === $serviceGatekeeperConsumer) {
            $SelectConsumer->setDefaultValue( Gatekeeper::serviceConsumer()->entityConsumerBySession()->getId() );
        }

        $View = new Stage();
        $View->setTitle( 'Mein Account' );
        $View->setDescription( 'Mandant ändern' );
        $View->setContent( Gatekeeper::serviceAccount()->executeChangeConsumer(
            new FormDefault(
                new GridFormGroup(
                    new GridFormRow( array(
                        new GridFormCol( $SelectConsumer )
                    ) )
                ), new ButtonSubmitPrimary( 'Neuen Mandant speichern' )
            ), $serviceGatekeeperConsumer
        ) );
        return $View;
    }
}
