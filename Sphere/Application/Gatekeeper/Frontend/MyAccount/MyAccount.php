<?php
namespace KREDA\Sphere\Application\Gatekeeper\Frontend\MyAccount;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RepeatIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\AbstractFrontend\Address\Structure\AddressDefault;
use KREDA\Sphere\Common\AbstractFrontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\AbstractFrontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\AbstractFrontend\Form\Element\InputPassword;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\AbstractFrontend\Table\Structure\GridTableBody;
use KREDA\Sphere\Common\AbstractFrontend\Table\Structure\GridTableCol;
use KREDA\Sphere\Common\AbstractFrontend\Table\Structure\GridTableHead;
use KREDA\Sphere\Common\AbstractFrontend\Table\Structure\GridTableRow;
use KREDA\Sphere\Common\AbstractFrontend\Table\Structure\TableDefault;

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
    public static function stageSummary()
    {

        $View = new Stage();
        $View->setTitle( 'Mein Account' );
        $View->setDescription( 'Zusammenfassung' );
        $tblAccount = Gatekeeper::serviceAccount()->entityAccountBySession();
        $tblPerson = $tblAccount->getServiceManagementPerson();
        $tblConsumer = $tblAccount->getServiceGatekeeperConsumer();
        $View->setContent(
            new TableDefault(
                new GridTableHead(
                    new GridTableRow(
                        new GridTableCol( 'Account', 2 )
                    )
                ),
                new GridTableBody( array(
                    new GridTableRow( array(
                        new GridTableCol( 'Benutzername', 1, '20%' ),
                        new GridTableCol( $tblAccount->getUsername() )
                    ) ),
                    new GridTableRow( array(
                        new GridTableCol( 'Zugangstyp' ),
                        new GridTableCol( $tblAccount->getTblAccountTyp()->getName() )
                    ) ),
                    new GridTableRow( array(
                        new GridTableCol( 'Berechtigungsstufe' ),
                        new GridTableCol( $tblAccount->getTblAccountRole()->getName() )
                    ) )
                ) )
            )
            .( $tblPerson ? new TableDefault(
                new GridTableHead(
                    new GridTableRow(
                        new GridTableCol( 'Benutzerdaten', 2 )
                    )
                ),
                new GridTableBody( array(
                    new GridTableRow( array(
                        new GridTableCol( 'Name', 1, '20%' ),
                        new GridTableCol(
                            $tblPerson->getSalutation()
                            .$tblPerson->getFirstName()
                            .$tblPerson->getMiddleName()
                            .$tblPerson->getLastName()
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
                ),
                new GridTableBody( array(
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
                        new GridTableCol( new AddressDefault( $tblConsumer->getServiceManagementAddress() ) )
                    ) )
                ) )
            ) : new MessageWarning( 'Keine Mandantendaten verfügbar', new WarningIcon() ) )
        //.new Account( Gatekeeper::serviceAccount()->entityAccountBySession() )
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
                            new InputPassword( 'CredentialLockSafety', 'Passwort wiederholen', 'Passwort wiederholen', new RepeatIcon() )
                        , 6 )
                    ) )
                ), new ButtonSubmitPrimary('Neues Passwort speichern' )
            ), $CredentialLock, $CredentialLockSafety
        ) );
        return $View;
    }
}
