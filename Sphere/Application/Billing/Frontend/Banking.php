<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtorCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ListIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MinusIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PlusIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
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

        $tblDebtorAll = Billing::serviceBanking()->entityDebtorAll();

        if (!empty( $tblDebtorAll )) {
            array_walk( $tblDebtorAll, function ( TblDebtor &$tblDebtor ) {

                $tblDebtor->Person = Management::servicePerson()->entityPersonById($tblDebtor->getServiceManagement_Person())->getFullName();
                $tblDebtor->Option =
                    (new Danger( 'Löschen', '/Sphere/Billing/Banking/Delete',
                        new RemoveIcon(), array(
                            'Id' => $tblDebtor->getId()
                        ) ) )->__toString().
                    (new Primary( 'Leistung auswählen', '/Sphere/Billing/Banking/Select/Commodity',
                        new ListIcon(), array(
                            'Id' => $tblDebtor->getId()
                        ) ))->__toString();
            } );
        }

        $View->setContent(
            new Layout(array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new TableData( $tblDebtorAll, null,
                                array(
                                    'DebtorNumber' => 'Debitorennummer',
                                    'LeadTimeFirst' => 'Ersteinzug',
                                    'LeadTimeFollow' => 'Folgeeinzug',
                                    'Person' => 'Person',
                                    'Option' => 'Bearbeiten'
                                )),
                        ))
                    )),
                )))));

        return $View;
    }

    /**
     * @param $Id
     * @return Stage
     */
    public static function frontendBankingDelete( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Debitor' );
        $View->setDescription( 'Entfernen' );

        $tblDebtor = Billing::serviceBanking()->entityDebtorById( $Id );
        $View->setContent(Billing::serviceBanking()->executeBankingDelete( $tblDebtor ));

        return $View;
    }

    /**
     * @param $Id
     * @return Stage
     */
    public static function frontendBankingSelectCommodity( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Leistungen' );
        $View->setDescription( 'Hinzufügen' );

        $IdPerson = Billing::serviceBanking()->entityDebtorById( $Id )->getServiceManagement_Person();
        $Person = Management::servicePerson()->entityPersonById( $IdPerson )->getFullName();
        $View->setMessage('Name: '.$Person.'<br/> Debitorennummer: '.Billing::serviceBanking()->entityDebtorById( $Id )->getDebtorNumber());

        $tblDebtor = Billing::serviceBanking()->entityDebtorById( $Id );
        $tblCommodityAll = Billing::serviceCommodity()->entityCommodityAll();

        $tblDebtorCommodityList = Billing::serviceBanking()->entityCommodityDebtorAllByDebtor( $tblDebtor );
        $tblCommodityByDebtorList = Billing::serviceBanking()->entityCommodityAllByDebtor( $tblDebtor );

        if (!empty( $tblCommodityByDebtorList )) {
            $tblCommodityAll = array_udiff( $tblCommodityAll, $tblCommodityByDebtorList,
                function ( TblCommodity $ObjectA, TblCommodity $ObjectB ) {

                    return $ObjectA->getId() - $ObjectB->getId();
                }
            );
        }

        if (!empty( $tblDebtorCommodityList )) {
            array_walk( $tblDebtorCommodityList, function ( TblDebtorCommodity &$tblDebtorCommodity ) {

                $tblCommodity = $tblDebtorCommodity->getServiceBillingCommodity();
                $tblDebtorCommodity->Name = $tblCommodity->getName();
                $tblDebtorCommodity->Description = $tblCommodity->getDescription();
                $tblDebtorCommodity->Option =
                    ( new Danger( 'Entfernen', '/Sphere/Billing/Banking/Commodity/Remove',
                        new MinusIcon(), array(
                            'Id' => $tblDebtorCommodity->getId()
                        ) ) )->__toString();
            } );
        }

        if (!empty( $tblCommodityAll )) {
            array_walk( $tblCommodityAll, function ( TblCommodity &$tblCommodity, $Index, TblDebtor $tblDebtor ) {

                $tblCommodity->Option =
                    ( new Primary( 'Hinzufügen', '/Sphere/Billing/Banking/Commodity/Add',
                        new PlusIcon(), array(
                            'Id' => $tblDebtor->getId(),
                            'CommodityId' => $tblCommodity->getId()
                        ) ) )->__toString();
            }, $tblDebtor );
        }

        $View->setContent(
            new Layout( array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new TableData( $tblDebtorCommodityList, null,
                                    array(
                                        'Name'        => 'Name',
                                        'Description' => 'Beschreibung',
                                        'Type'        => 'Leistungsart',
                                        'Option' => 'Option'
                                    )
                                )
                            )
                        )
                    ) ),
                ), new LayoutTitle( 'zugewiesene Leistungen' ) ),
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new TableData( $tblCommodityAll, null,
                                    array(
                                        'Name'        => 'Name',
                                        'Description' => 'Beschreibung',
                                        'Type'        => 'Leistungsart',
                                        'Option' => 'Option'
                                    )
                                )
                            )
                        )
                    ) ),
                ), new LayoutTitle( 'mögliche Leistungen' ) )
            ) )
        );

        return $View;
    }

    /**
     * @return Stage
     */
    public static function frontendBankingRemoveCommodity( $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Leistung' );
        $View->setDescription( 'Entfernen' );

        $tblDebtorCommodity = Billing::serviceBanking()->entityDebtorCommodityById( $Id );
        $View->setContent( Billing::serviceBanking()->executeRemoveDebtorCommodity( $tblDebtorCommodity ) );

        return $View;
    }

    /**
     * @param $Id
     * @param $CommodityId
     * @return Stage
     */
    public static function frontendBankingAddCommodity( $Id, $CommodityId )
    {

        $View = new Stage();
        $View->setTitle( 'Leistung' );
        $View->setDescription( 'Hinzufügen' );

        $tblDebtor = Billing::serviceBanking()->entityDebtorById( $Id );
        $tblCommodity = Billing::serviceCommodity()->entityCommodityById( $CommodityId );
        $View->setContent( Billing::serviceBanking()->executeAddDebtorCommodity( $tblDebtor, $tblCommodity ) );

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
                                    )),
                            ))
                    )),
                )))));
        return $View;
    }

    /**
     * @param $Debtor
     * @param $Id
     * @return Stage
     */
    public static function frontendBankingPersonSelect( $Debtor, $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Debitoreninformationen' );

        $Person = Management::servicePerson()->entityPersonById( $Id )->getFullName();
        $View->setMessage( $Person );

        if ( Billing::serviceBanking()->entityDebtorByServiceManagement_Person( $Id ) == false )
        {
            $View->setContent(
                Billing::serviceBanking()->executeAddDebtor(
                    new Form( array(
                        new FormGroup( array(
                            new FormRow( array(

                                new FormColumn(
                                    new TextField( 'Debtor[DebtorNumber]', 'Debitornummer', 'Debitornummer', new ConversationIcon()
                                    ), 12),
                                new FormColumn(
                                    new TextField( 'Debtor[LeadTimeFirst]', 'Vorlaufzeit in Tagen', 'Ersteinzug', new ConversationIcon()
                                    ), 6),
                                new FormColumn(
                                    new TextField( 'Debtor[LeadTimeFollow]', 'Vorlaufzeit in Tagen', 'Folgeeinzug', new ConversationIcon()
                                    ), 6),
                )),
            ))),  new SubmitPrimary( 'Hinzufügen' )), $Debtor, $Id ));
        }
        else{

            $tblDebtor = Billing::serviceBanking()->entityDebtorByServiceManagement_Person( $Id );

            $View->setContent(
                Billing::serviceBanking()->executeAddDebtor(
                    new Form( array(
                        new FormGroup( array(
                            new FormRow( array(

                                new FormColumn(
                                    new TableData( $tblDebtor, null,
                                    array( 'DebtorNumber' => 'Debitorennummer',
                                           'LeadTimeFirst' => 'Ersteinzug',
                                           'LeadTimeFollow' => 'Folgeeinzug'
                                    )
                                )))))),
                        new FormGroup( array(
                            new FormRow( array(

                                new FormColumn(
                                    new TextField( 'Debtor[DebtorNumber]', 'Debitornummer', 'Debitornummer', new ConversationIcon()
                                    ), 12),
                                new FormColumn(
                                    new TextField( 'Debtor[LeadTimeFirst]', 'Vorlaufzeit in Tagen', 'Ersteinzug', new ConversationIcon()
                                    ), 6),
                                new FormColumn(
                                    new TextField( 'Debtor[LeadTimeFollow]', 'Vorlaufzeit in Tagen', 'Folgeeinzug', new ConversationIcon()
                                    ), 6),
                            )),
                        )),

                    ),  new SubmitPrimary( 'Hinzufügen' )), $Debtor, $Id ));
        }

        return $View;
    }

}