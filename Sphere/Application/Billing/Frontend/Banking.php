<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PlusIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Input\Type\HiddenField;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;

/**
 * Class Account
 *
 * @package KREDA\Sphere\Application\Billing\Frontend
 */
class Banking extends AbstractFrontend
{
    /**
     * @return Stage
     */
    public static function frontendBanking()
    {
        $View = new Stage();
        $View->setTitle( 'Debitoren Übersicht' );
        $View->addButton(
            new Primary( 'Debitor Anlegen', '/Sphere/Billing/Banking/Person', new PlusIcon() )
        );



        return $View;
    }

    /**
     * @return Stage
     */
    public static function frontendBankingPerson()
    {
        $View = new Stage();
        $View->setTitle( 'Debitorensuche' );

        $tblPerson = Management::servicePerson()->entityPersonAll();

        if (!empty($tblPerson))
        {
            foreach ($tblPerson as $Person)
            {
                $Person->Option=
                    ( ( new Primary( 'Weiter Bearbeiten', '/Sphere/Billing/Banking/Person/Select',
                        new EditIcon(), array(
                            'Id' => $Person->getId()
                        ) ) )->__toString());
            }
        }



        $View->setContent(
            new Layout(array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new TableData( $tblPerson, null,
                                    array(
                                        'FirstName' => 'Vorname',
                                        'MiddleName' => 'Zweitname',
                                        'LastName' => 'Nachname',
                                        'Option' => 'Debitor hinzufügen'
                                    )
                                ),
                            )
                        )
                    ) ),
                )))));
        return $View;
    }

    public static function frontendBankingPersonSelect( $Debtor, $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Debitoreninformationen' );




        $View->setContent(Billing::serviceBanking()->executeAddDebtor(
            new Form( array(
                new FormGroup( array(
                    new FormRow( array(

                        new FormColumn(
                            new TextField( 'Debtor[DebtorNumber]', 'Debitornummer', 'Debitornummer', new ConversationIcon()
                            ), 6),
                        new FormColumn(
                            new TextField( 'Debtor[LeadTimeFirst]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                            ), 6),
                    )),
                    new FormRow( array(
                        new FormColumn(
                            new TextField( 'Debtor[LeadTimeFollow]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                                ) )
                            , 12 ),
                        new FormColumn(
                            new HiddenField( 'Debtor[ServiceManagement_Person]', 'Beschreibung', 'Beschreibung'
                                ) )
                            , 6 )

                ))),  new SubmitPrimary( 'Hinzufügen' )), $Debtor, $Id ));

        return $View;
    }

}