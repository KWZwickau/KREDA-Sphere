<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtorCommodity;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblPaymentType;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblReference;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BarCodeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BuildingIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ChevronLeftIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DisableIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EnableIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MinusIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MoneyIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\NameplateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PlusIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle;
use KREDA\Sphere\Client\Frontend\Input\Type\DatePicker;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutLabel;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutPanel;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Client\Frontend\Text\Type\Success;
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
        $View->setTitle( 'Debitoren' );
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Zeigt die verfügbaren Debitoren an' );
        $View->addButton(
            new Primary( 'Debitor anlegen', '/Sphere/Billing/Banking/Person', new PlusIcon() )
        );

        $tblDebtorAll = Billing::serviceBanking()->entityDebtorAll();

        if (!empty( $tblDebtorAll )) {
            array_walk( $tblDebtorAll, function ( TblDebtor &$tblDebtor ) {
                $referenceCommodityList = Billing::serviceBanking()->entityReferenceByDebtor( $tblDebtor );
                $referenceCommodity = '';
                if($referenceCommodityList)
                {
                    for ($i = 0; $i < count($referenceCommodityList); $i++)
                    {
                        $tblCommodity = $referenceCommodityList[$i]->getServiceBillingCommodity();
                        if ($tblCommodity)
                        {
                            if ($i === 0)
                            {
                                $referenceCommodity .= $tblCommodity->getName();
                            }
                            else
                            {
                                $referenceCommodity .= ', ' . $tblCommodity->getName() ;
                            }
                        }
                    }
                }
                $tblDebtor->ReferenceCommodity = $referenceCommodity;

                $debtorCommodityList = Billing::serviceBanking()->entityCommodityDebtorAllByDebtor( $tblDebtor );
                $debtorCommodity = '';
                if($debtorCommodityList)
                {
                    for ($i = 0; $i < count($debtorCommodityList); $i++)
                    {
                        $tblCommodity = $debtorCommodityList[$i]->getServiceBillingCommodity();
                        if ($tblCommodity)
                        {
                            if ($i === 0)
                            {
                                $debtorCommodity .= $tblCommodity->getName();
                            }
                            else
                            {
                                $debtorCommodity .= ', ' . $tblCommodity->getName() ;
                            }
                        }
                    }
                }
                $tblDebtor->DebtorCommodity = $debtorCommodity;


                $tblPerson = $tblDebtor->getServiceManagementPerson();
                if(!empty($tblPerson))
                {
                    $tblDebtor->FirstName = $tblPerson->getFirstName();
                    $tblDebtor->LastName = $tblPerson->getLastName();
                }
                else
                {
                    $tblDebtor->FirstName = 'Person nicht vorhanden';
                    $tblDebtor->LastName = 'Person nicht vorhanden';
                }

                $tblDebtor->Edit =
                    (new Primary( 'Bearbeiten', '/Sphere/Billing/Banking/Debtor/View',
                        new EditIcon(), array(
                            'Id' => $tblDebtor->getId()
                        ) ) )->__toString().
                    (new Danger( 'Löschen', '/Sphere/Billing/Banking/Delete',
                        new RemoveIcon(), array(
                            'Id' => $tblDebtor->getId()
                        ) ) )->__toString();

                $Bankname = $tblDebtor->getBankName();
                $IBAN = $tblDebtor->getIBANFrontend();
                $BIC = $tblDebtor->getBIC();
                $Owner = $tblDebtor->getOwner();
                if(!empty( $Bankname ) && !empty( $IBAN ) && !empty( $BIC ) && !empty( $Owner ) )
                {
                    $tblDebtor->BankInformation = new LayoutLabel( new EnableIcon().' OK', LayoutLabel::LABEL_TYPE_SUCCESS );
                }
                else
                {
                    $tblDebtor->BankInformation = new LayoutLabel( new DisableIcon().' Nicht OK', LayoutLabel::LABEL_TYPE_DANGER );
                }

            } );
        }

        $View->setContent(
            new Layout(array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new TableData( $tblDebtorAll, null,
                                array(
                                    'DebtorNumber' => 'Debitoren-Nr',
                                    'FirstName' => 'Vorname',
                                    'LastName' => 'Nachname',
                                    'ReferenceCommodity' => 'Mandatsreferenzen',
                                    'DebtorCommodity' => 'Leistungszuordnung',
                                    'BankInformation' => 'Bankdaten',
                                    'Edit' => 'Verwaltung'
                                ))
                            ))
                        ))
                    ))
                ))
            );

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingDebtorView( $Id )
    {
        $View = new Stage();
        $View->setTitle('Debitor');
        $View->addButton( new Primary('Zurück', '/Sphere/Billing/Banking', new ChevronLeftIcon()) );
        $tblDebtor = Billing::serviceBanking()->entityDebtorById( $Id );
        $tblPerson = $tblDebtor->getServiceManagementPerson();

        $View->setContent(
            new Layout(array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutPanel( 'Name Debitor', $tblPerson->getFullName(), LayoutPanel::PANEL_TYPE_INFO )
                        ), 4),
                        new LayoutColumn( array(
                            new LayoutPanel( 'Debitornummer', $tblDebtor->getDebtorNumber(), LayoutPanel::PANEL_TYPE_INFO )
                        ), 4),
                        new LayoutColumn( array(
                            new LayoutPanel( 'Bezahlart', $tblDebtor->getPaymentType()->getName(), LayoutPanel::PANEL_TYPE_WARNING )
                        ), 4),
                    )),
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutPanel( 'Kontoinhaber', $tblDebtor->getOwner(), LayoutPanel::PANEL_TYPE_WARNING )
                        ), 4),
                        new LayoutColumn( array(
                            new LayoutPanel( 'IBAN', $tblDebtor->getIBANFrontend(), LayoutPanel::PANEL_TYPE_WARNING )
                        ), 4),
                        new LayoutColumn( array(
                            new LayoutPanel( 'BIC', $tblDebtor->getBIC(), LayoutPanel::PANEL_TYPE_WARNING )
                        ), 4),
                    )),
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutPanel( 'Bank', $tblDebtor->getBankName(), LayoutPanel::PANEL_TYPE_DEFAULT )
                        ), 4),
                        new LayoutColumn( array(
                            new LayoutPanel( 'Bankzeichen', $tblDebtor->getCashSign(), LayoutPanel::PANEL_TYPE_DEFAULT )
                        ), 4),
                        new LayoutColumn( array(
                            new LayoutPanel( 'Ersteinzug', $tblDebtor->getLeadTimeFirst(), LayoutPanel::PANEL_TYPE_DEFAULT )
                        ), 2),
                        new LayoutColumn( array(
                            new LayoutPanel( 'Folgeeinzug', $tblDebtor->getLeadTimeFollow(), LayoutPanel::PANEL_TYPE_DEFAULT )
                        ), 2),
                    )),
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutPanel( 'Beschreibung', $tblDebtor->getDescription(), LayoutPanel::PANEL_TYPE_DEFAULT )
                        ), 12),
                    ))
                ), new LayoutTitle( 'Bankdaten' ))
            ))
            .new Primary( 'Bearbeiten', '/Sphere/Billing/Banking/Debtor/Edit', null, array( 'Id' => $Id ) )
            .self::layoutCommodityDebtor( $tblDebtor )
            .new Primary( 'Bearbeiten', '/Sphere/Billing/Banking/Commodity/Select', null, array( 'Id' => $Id ) )
            .self::layoutReference( $tblDebtor )
            .new Primary( 'Bearbeiten', '/Sphere/Billing/Banking/Debtor/Reference', null, array( 'Id' => $Id ) )
        );

        return $View;
    }

    /**
     * @param TblDebtor $tblDebtor
     *
     * @return Layout
     */
    public static function layoutCommodityDebtor( TblDebtor $tblDebtor )
    {
        $tblCommodityList = Billing::serviceBanking()->entityCommodityAllByDebtor( $tblDebtor );
        if (!empty( $tblCommodityList ))
        {
            /** @var TblCommodity $tblCommodity */
            foreach ($tblCommodityList as $Key => &$tblCommodity)
            {

                $tblReference = Billing::serviceBanking()->entityReferenceByDebtorAndCommodity( $tblDebtor, $tblCommodity );

                if($tblReference)
                {
                    $tblCommodity = new LayoutColumn(array(
                        new LayoutPanel( $tblCommodity->getName(),null, LayoutPanel::PANEL_TYPE_SUCCESS ) ),3);
                }
                else
                {
                    $tblCommodity = new LayoutColumn(array(
                        new LayoutPanel( $tblCommodity->getName(),null, LayoutPanel::PANEL_TYPE_DANGER ) ),3);
                }
            }
        }
        return new Layout(
            new LayoutGroup( new LayoutRow( $tblCommodityList ), new LayoutTitle( 'Leistungen' ) )
        );
    }

    /**
     * @param TblDebtor $tblDebtor
     *
     * @return Layout
     */
    public static function layoutReference( TblDebtor $tblDebtor )
    {
        $tblReferenceList = Billing::serviceBanking()->entityReferenceByDebtor( $tblDebtor );
        if (!empty($tblReferenceList))
        {
            /** @var TblReference $tblReference */
            foreach ($tblReferenceList as $Key => &$tblReference)
            {
                $Reference = $tblReference->getServiceBillingCommodity()->getName();

                $tblReference = new LayoutColumn(array(
                    new LayoutPanel( $Reference, $tblReference->getReference(), LayoutPanel::PANEL_TYPE_SUCCESS ) ),3);
            }
        }
        return new Layout(
            new LayoutGroup( new LayoutRow( $tblReferenceList ), new LayoutTitle( 'Referenzen' ) )
        );
    }

    /**
     * @param $Id
     *
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
     *
     * @return Stage
     */
    public static function frontendBankingCommoditySelect( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Leistungen' );
        $View->setDescription( 'Hinzufügen' );
        $View->setMessage( 'Gibt es mehrere Debitoren für eine Person, kann über die Leistung bestimmt werden, welcher Debitor welche Leistung bezahlen soll.<br />
                            Ist die Vorauswahl nicht getroffen, wird bei unklarem Debitor an entsprechender Stelle gefragt.' );
        $View->addButton( new Primary( 'Zurück','/Sphere/Billing/Banking/Debtor/View', new ChevronLeftIcon(), array( 'Id' => $Id ) ) );

        $IdPerson = Billing::serviceBanking()->entityDebtorById( $Id )->getServiceManagementPerson();
        $tblPerson = Management::servicePerson()->entityPersonById( $IdPerson );
        if(!empty($tblPerson))
        {
            $Person = $tblPerson->getFullName();
        }
        else
        {
            $Person = 'Person nicht vorhanden';
        }
        $DebtorNumber = Billing::serviceBanking()->entityDebtorById( $Id )->getDebtorNumber();
//        $View->setMessage('Name: '.$Person.'<br/> Debitorennummer: '.Billing::serviceBanking()->entityDebtorById( $Id )->getDebtorNumber());

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

                $tblReference = Billing::serviceBanking()->entityReferenceByDebtorAndCommodity( $tblDebtorCommodity->getTblDebtor(), $tblDebtorCommodity->getServiceBillingCommodity() );
                if ($tblReference)
                {
                    $tblDebtorCommodity->Ready = new LayoutLabel( 'Ref. ok', LayoutLabel::LABEL_TYPE_SUCCESS );
                }
                else
                {
                    $tblDebtorCommodity->Ready = new LayoutLabel( 'Ref. fehlt', LayoutLabel::LABEL_TYPE_DANGER );
                }

                $tblCommodity = $tblDebtorCommodity->getServiceBillingCommodity();
                $tblDebtorCommodity->Name = $tblCommodity->getName();
                $tblDebtorCommodity->Description = $tblCommodity->getDescription();
                $tblDebtorCommodity->Type = $tblCommodity->getTblCommodityType()->getName();

                $tblDebtorCommodity->Option =
                    ( new Danger( 'Entfernen', '/Sphere/Billing/Banking/Commodity/Remove',
                        new MinusIcon(), array(
                            'Id' => $tblDebtorCommodity->getId()
                        ) ) )->__toString();
            } );
        }

        if (!empty( $tblCommodityAll )) {
            array_walk( $tblCommodityAll, function ( TblCommodity &$tblCommodity, $Index, TblDebtor $tblDebtor ) {

                $tblReference = Billing::serviceBanking()->entityReferenceByDebtorAndCommodity( $tblDebtor, $tblCommodity );

                if ($tblReference)
                {
                    $tblCommodity->Ready = new LayoutLabel( 'Ref. ok', LayoutLabel::LABEL_TYPE_SUCCESS );
                }
                else
                {
                    $tblCommodity->Ready = new LayoutLabel( 'Ref. fehlt', LayoutLabel::LABEL_TYPE_DANGER );
                }

                $tblCommodity->Type = $tblCommodity->getTblCommodityType()->getName();
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
                                new LayoutPanel( new PersonIcon(). ' Debitor', $Person, LayoutPanel::PANEL_TYPE_SUCCESS
                                    )
                                ),6),
                            new LayoutColumn( array(
                                new LayoutPanel( new BarCodeIcon(). ' Debitornummer', $DebtorNumber, LayoutPanel::PANEL_TYPE_SUCCESS
                                    )
                                ),6)
                            ))
                ) /*, new LayoutTitle( 'Debitor' )*/ ),
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new TableData( $tblDebtorCommodityList, null,
                                    array(
                                        'Name'        => 'Name',
                                        'Description' => 'Beschreibung',
                                        'Type'        => 'Leistungsart',
                                        'Ready'        => 'Referenz',
                                        'Option' => 'Option'
                                    ))
                                ))
                            )),
                ), new LayoutTitle( 'zugewiesene Leistungen' ) ),
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new TableData( $tblCommodityAll, null,
                                    array(
                                        'Name'        => 'Name',
                                        'Description' => 'Beschreibung',
                                        'Type'        => 'Leistungsart',
                                        'Ready'        => 'Referenz',
                                        'Option' => 'Option'
                                    ))
                                ))
                            )),
                        ), new LayoutTitle( 'mögliche Leistungen' ) )
                    ))
                );

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingCommodityRemove( $Id )
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
     *
     * @return Stage
     */
    public static function frontendBankingCommodityAdd( $Id, $CommodityId )
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
        $View->addButton( new Primary( 'Zurück','/Sphere/Billing/Banking', new ChevronLeftIcon() ) );

        $tblPerson = Management::servicePerson()->entityPersonAll();

        if (!empty($tblPerson))
        {
            foreach ($tblPerson as $Person)
            {
                $PersonType = Management::servicePerson()->entityPersonById( $Person->getId() )->getTblPersonType();
                $Person->Option=
                    ( ( new Primary( 'Debitor erstellen', '/Sphere/Billing/Banking/Person/Select',
                        new EditIcon(), array(
                            'Id' => $Person->getId()
                        ) ) )->__toString());
                $Person->PersonType = $PersonType->getName();
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
                                        'PersonType' => 'Persontyp',
                                        'Option' => 'Debitor hinzufügen'
                                    )),
                                ))
                            ))
                        ))
                    ))
                );
        return $View;
    }

//    /**
//     * @param $Id
//     *
//     * @return Stage
//     */
//    public static function frontendBankingReferenceDelete( $Id )
//    {
//        $View = new Stage();
//        $View->setTitle( 'Referenz' );
//        $View->setDescription( 'entfernt' );
//        $Debtor = Billing::serviceBanking()->entityDebtorById( $Id );
//        $View->setContent(
//            Billing::serviceBanking()->executeDeleteReference( $Debtor ) );
//
//        return $View;
//    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingReferenceDeactivate( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Referenz' );
        $View->setDescription( 'deaktiviert' );

        $tblReference = Billing::serviceBanking()->entityReferenceById( $Id );
        $View->setContent(Billing::serviceBanking()->setBankingReferenceDeactivate( $tblReference ));

        return $View;
    }

    /**
     * @param $Id
     * @param $Debtor
     *
     * @return Stage
     */
    public static function frontendBankingDebtorEdit( $Id, $Debtor )
    {
        $View = new Stage();
        $View->setTitle( 'Bankdaten' );
        $View->setDescription( 'bearbeiten' );
        $View->addButton( new Primary( 'Zurück','/Sphere/Billing/Banking/Debtor/View', new ChevronLeftIcon(), array( 'Id' => $Id ) ) );
        $tblDebtor = Billing::serviceBanking()->entityDebtorById( $Id );
        $Person = Management::servicePerson()->entityPersonById( $tblDebtor->getServiceManagementPerson() );
        if (!empty($Person))
        {
            $Name = $Person->getFullName();
        }
        else
        {
            $Name = 'Person nicht vorhanden';
        }

        $DebtorNumber = $tblDebtor->getDebtorNumber();

        $tblPaymentType = Billing::serviceBanking()->entityPaymentTypeAll();
        $PaymentType = Billing::serviceBanking()->entityPaymentTypeById( Billing::serviceBanking()->entityDebtorById( $Id )->getPaymentType() )->getName();

        $Global = self::extensionSuperGlobal();
        if (!isset( $Global->POST['Debtor']) )
        {
            $Global->POST['Debtor']['Description'] = $tblDebtor->getDescription();
            $Global->POST['Debtor']['PaymentType'] = Billing::serviceBanking()->entityPaymentTypeByName( $PaymentType )->getId();
            $Global->POST['Debtor']['Owner'] = $tblDebtor->getOwner();
            $Global->POST['Debtor']['IBAN'] = $tblDebtor->getIBANFrontend();
            $Global->POST['Debtor']['BIC'] = $tblDebtor->getBIC();
            $Global->POST['Debtor']['CashSign'] = $tblDebtor->getCashSign();
            $Global->POST['Debtor']['BankName'] = $tblDebtor->getBankName();
            $Global->POST['Debtor']['LeadTimeFirst'] = $tblDebtor->getLeadTimeFirst();
            $Global->POST['Debtor']['LeadTimeFollow'] = $tblDebtor->getLeadTimeFollow();
            $Global->savePost();
        }

            $View->setContent(
                new Layout( array(
                    new LayoutGroup( array(
                        new LayoutRow( array(
                            new LayoutColumn( array(
                                new LayoutPanel( new PersonIcon(). ' Person', $Name, LayoutPanel::PANEL_TYPE_SUCCESS )
                            ),6),
                            new LayoutColumn( array(
                                new LayoutPanel( new BarCodeIcon(). ' Debitornummer', $DebtorNumber, LayoutPanel::PANEL_TYPE_SUCCESS )
                            ),6),
                            new LayoutColumn( array(
                                Billing::serviceBanking()->executeEditDebtor(
                                    new Form( array(
                                        new FormGroup( array(
                                            new FormRow( array(
                                                new FormColumn(
                                                    new TextField( 'Debtor[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                                                    ), 12 ),
                                                new FormColumn(
                                                    new SelectBox( 'Debtor[PaymentType]', 'Bezahlmethode', array( TblPaymentType::ATTR_NAME => $tblPaymentType ) , new ConversationIcon()
                                                    ), 4 ),
                                                new FormColumn(
                                                    new TextField( 'Debtor[LeadTimeFirst]', 'In Tagen', 'Ersteinzug', new TimeIcon()
                                                    ), 4 ),
                                                new FormColumn(
                                                    new TextField( 'Debtor[LeadTimeFollow]', 'In Tagen', 'Folgeeinzug', new TimeIcon()
                                                    ), 4 ),
                                            ))
                                        ), new FormTitle('Debitor') ),
                                        new FormGroup( array(
                                            new FormRow( array(
                                                new FormColumn(
                                                    new TextField( 'Debtor[Owner]', 'Vorname Nachname', 'Inhaber', new PersonIcon()
                                                    ), 6 ),
                                                new FormColumn(
                                                    new TextField( 'Debtor[BankName]', 'Bank', 'Name der Bank', new BuildingIcon()
                                                    ), 6 ),
                                                new FormColumn(
                                                    new TextField( 'Debtor[IBAN]', 'XX XX XXXXXXXX XXXXXXXXXX', 'IBAN', new BarCodeIcon()
                                                    ), 4 ),
                                                new FormColumn(
                                                    new TextField( 'Debtor[BIC]', 'XXXX XX XX XXX', 'BIC', new BarCodeIcon()
                                                    ), 4 ),
                                                new FormColumn(
                                                    new TextField( 'Debtor[CashSign]', ' ', 'Kassenzeichen', new NameplateIcon()
                                                    ), 4 ),
                                            ) )
                                        ), new FormTitle('Bankdaten'))
                                ), new SubmitPrimary( 'Änderungen speichern' )),$tblDebtor, $Debtor)
                            )),
                        ))
                    ))
                ))
            );

        return $View;
    }

    public static function frontendBankingDebtorReference ( $Id, $Reference )
    {
        $View = new Stage();

        $View->setTitle( 'Referenzen' );
        $View->setDescription( 'bearbeiten' );
        $View->setMessage( 'Die Referenzen sind eine vom Auftraggeber der Zahlung vergebene Kennzeichnung.<br />
                            Hier kann z.B. eine Vertrags- oder Rechnungsnummer eingetragen werden.<br />
                            Referenzen müssen eindeutig sein!' );
        $View->addButton( new Primary( 'Zurück','/Sphere/Billing/Banking/Debtor/View', new ChevronLeftIcon(), array( 'Id' => $Id ) ) );
        $tblDebtor = Billing::serviceBanking()->entityDebtorById( $Id );
        $Person = Management::servicePerson()->entityPersonById( $tblDebtor->getServiceManagementPerson() );
        if (!empty($Person))
        {
            $Name = $Person->getFullName();
            $tblDebtorList = Billing::serviceBanking()->entityDebtorAllByPerson( $Person );
        }
        else
        {
            $Name = 'Person nicht vorhanden';
            $tblDebtorList = false;
        }

        $DebtorNumber = $tblDebtor->getDebtorNumber();
        $ReferenceEntityList = Billing::serviceBanking()->entityReferenceByDebtor( $tblDebtor );

        $DebtorArray = array();
        $DebtorArray[] = $tblDebtor;
        if ($tblDebtorList && $DebtorArray)
        {
            $tblDebtorList = array_udiff($tblDebtorList, $DebtorArray,
                function(TblDebtor $invoiceA, TblDebtor $invoiceB){
                    return $invoiceA->getId() - $invoiceB->getId();
                });

            /** @var TblDebtor $DebtorOne */
            foreach($tblDebtorList as $DebtorOne)
            {
                $DebtorOne->IBANfrontend = $DebtorOne->getIBANFrontend();
            }

        }

        if ($ReferenceEntityList)
        {
            foreach ($ReferenceEntityList as $ReferenceEntity)
            {
                $ReferenceReal = Billing::serviceCommodity()->entityCommodityById( $ReferenceEntity->getServiceBillingCommodity() );
                if($ReferenceReal !== false)
                { $ReferenceEntity->Commodity = $ReferenceReal->getName(); }
                else
                { $ReferenceEntity->Commodity = 'Leistung nicht vorhanden'; }

                $tblComparList = Billing::serviceBanking()->entityDebtorCommodityAllByDebtorAndCommodity( $ReferenceEntity->getServiceBillingBanking(), $ReferenceEntity->getServiceBillingCommodity() );

                if($tblComparList)
                {
                    $ReferenceEntity->Usage = new Success( new EnableIcon().' In Verwendung' );
                }
                else
                {
                    $ReferenceEntity->Usage = '';
                }

                $ReferenceEntity->Option =
                    (new Danger( 'Deaktivieren', '/Sphere/Billing/Banking/Reference/Select/Deactivate',
                        new RemoveIcon(), array(
                            'Id' => $ReferenceEntity->getId()
                        ) ))->__toString();
            }
        }

        $tblCommoditySelectBox = Billing::serviceCommodity()->entityCommodityAll();
        $tblReferenceList = Billing::serviceBanking()->entityReferenceByDebtor( $tblDebtor );
        $tblCommodityUsed = array();
        foreach($tblReferenceList as $tblReference)
        {
            $tblCommodityUsedReal = Billing::serviceCommodity()->entityCommodityById( $tblReference->getServiceBillingCommodity() );
            if($tblCommodityUsedReal !== false)
            { $tblCommodityUsed[] = $tblCommodityUsedReal; }
        }
        if ($tblCommoditySelectBox && $tblCommodityUsed)
        {
            $tblCommoditySelectBox = array_udiff($tblCommoditySelectBox, $tblCommodityUsed,
                function(TblCommodity $invoiceA, TblCommodity $invoiceB){
                    return $invoiceA->getId() - $invoiceB->getId();
                });
        }

        $View->setContent(
            new Layout( array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutPanel( new PersonIcon(). ' Person', $Name, LayoutPanel::PANEL_TYPE_SUCCESS )
                        ),6),
                        new LayoutColumn( array(
                            new LayoutPanel( new BarCodeIcon(). ' Debitornummer', $DebtorNumber, LayoutPanel::PANEL_TYPE_SUCCESS )
                        ),6),
                    ))
                )),
                ( !empty( $tblCommoditySelectBox ) ) ?
                    new LayoutGroup( array(
                        new LayoutRow( array(
                            new LayoutColumn( array(
                                Billing::serviceBanking()->executeAddReference(
                                    new Form( array(
                                        new FormGroup( array(
                                            new FormRow( array(
                                                new FormColumn(
                                                    new TextField( 'Reference[Reference]', 'Referenz', 'Mandatsreferenz', new BarCodeIcon()
                                                    ), 4),
                                                new FormColumn(
                                                    new DatePicker( 'Reference[ReferenceDate]', 'Datum', 'Erstellungsdatum', new TimeIcon()
                                                    ), 4),
                                                new FormColumn(
                                                    new SelectBox( 'Reference[Commodity]', 'Leistung', array( 'Name' => $tblCommoditySelectBox ) ,  new TimeIcon()
                                                    ), 4),
                                            )),
                                        ))
                                    ),  new SubmitPrimary( 'Hinzufügen' ))
                                , $tblDebtor, $Reference, $Id )
                            ))
                        ))
                    ), new LayoutTitle( 'Referenz hinzufügen' )) : null,
                        ( !empty($ReferenceEntityList) ) ?
                            new LayoutGroup( array(
                                new LayoutRow( array(
                                    new LayoutColumn( array(
                                        new TableData( $ReferenceEntityList, null,
                                            array(
                                                'Reference' => 'Mandatsreferenz',
                                                'ReferenceDate' => 'Datum',
                                                'Commodity' => 'Leistung',
                                                'Usage' => 'Benutzung',
                                                'Option' => 'Deaktivieren'
                                            ))
                                    ))
                                ))
                            ), new LayoutTitle( 'Mandatsreferenz' ) ) : null ,
                        ( count($tblDebtorList) >= 1 ) ?
                            new LayoutGroup( array(
                                new LayoutRow( array(
                                    new LayoutColumn( array(

                                        new Form( array(
                                            new FormGroup( array(
                                                new FormRow( array(
                                                    new FormColumn( array(
                                                        new TableData( $tblDebtorList, null, array(
                                                            'DebtorNumber' => 'Debitorennummer',
                                                            'BankName' => 'Name der Bank',
                                                            'IBANfrontend' => 'IBAN',
                                                            'BIC' => 'BIC',
                                                            'Owner' => 'Inhaber'
                                                        ))
                                                    ))
                                                ))
                                            ))
                                        ))
                                    ),12)
                                ))
                            ), new LayoutTitle( 'Weitere Debitorennummer(n)' ) )
                        : null ,
            ))
        );

        return $View;
    }

    /**
     * @param $Debtor
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingPersonSelect( $Debtor, $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Debitoreninformationen' );
        $View->addButton( new Primary( 'Zurück','/Sphere/Billing/Banking/Person', new ChevronLeftIcon() ) );

        $PersonName = Management::servicePerson()->entityPersonById( $Id )->getFullName();
        $PersonType = Management::servicePerson()->entityPersonById( $Id )->getTblPersonType();
        $tblPaymentType = Billing::serviceBanking()->entityPaymentTypeAll();
        $tblCommodity = Billing::serviceCommodity()->entityCommodityAll();
        $tblPerson = Management::servicePerson()->entityPersonById( $Id );

        $tblStudent = Management::serviceStudent()->entityStudentByPerson( $tblPerson );
        if ($tblStudent)
        {
            if ( $tblStudent->getStudentNumber() === 0 )
            {
                $tblStudent->setStudentNumber( 'Nicht vergeben' );
            }
        }

        $Global = self::extensionSuperGlobal();
        $Global->POST['Debtor']['Owner'] = $PersonName;

        if( !isset( $Global->POST['Debtor']['PaymentType'] ) ) {
            $Global->POST['Debtor']['PaymentType'] = Billing::serviceBanking()->entityPaymentTypeByName( 'SEPA-Lastschrift' )->getId();
        }
        if ( Billing::serviceBanking()->entityDebtorByServiceManagementPerson( $Id ) == true )
        {
            $tblDebtor = Billing::serviceBanking()->entityDebtorByServiceManagementPerson( $Id );
        }

        $Global->savePost();
        $View->setContent(
            new Layout( array(
                new LayoutGroup( array(
                    ( empty( $tblStudent ) ) ?
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutPanel( new PersonIcon().' Debitor', $PersonName, LayoutPanel::PANEL_TYPE_SUCCESS
                            )),6),
                        new LayoutColumn( array(
                            new LayoutPanel( new GroupIcon().'. Personengruppe', $PersonType->getName(), LayoutPanel::PANEL_TYPE_SUCCESS
                            )),6),
                    )): null,
                    ( !empty( $tblStudent ) ) ?
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutPanel( new PersonIcon().' Debitor', $PersonName, LayoutPanel::PANEL_TYPE_WARNING
                            )),4),
                        new LayoutColumn( array(
                            new LayoutPanel( new GroupIcon().'. Schülernummer', $tblStudent->getStudentNumber(), LayoutPanel::PANEL_TYPE_PRIMARY
                            )),4),
                        new LayoutColumn( array(
                            new LayoutPanel( new GroupIcon().'. Personengruppe', $PersonType->getName(), LayoutPanel::PANEL_TYPE_WARNING
                            )),4),
                    )): null,
                )),
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            Billing::serviceBanking()->executeAddDebtor(
                                new Form( array(
                                    new FormGroup( array(
                                        new FormRow( array(
                                            new FormColumn(
                                                new TextField( 'Debtor[DebtorNumber]', 'Debitornummer', 'Debitornummer', new BarCodeIcon()
                                                ), 12),
                                            new FormColumn(
                                                new SelectBox( 'Debtor[PaymentType]', 'Bezahlmethode', array( TblPaymentType::ATTR_NAME => $tblPaymentType ), new MoneyIcon()
                                                ), 4),
                                            new FormColumn(
                                                new TextField( 'Debtor[LeadTimeFirst]', 'Vorlaufzeit in Tagen', 'Ersteinzug', new TimeIcon()
                                                ), 4),
                                            new FormColumn(
                                                new TextField( 'Debtor[LeadTimeFollow]', 'Vorlaufzeit in Tagen', 'Folgeeinzug', new TimeIcon()
                                                ), 4),
                                            new FormColumn(
                                                new TextField( 'Debtor[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                                                ), 12),
                                        ))
                                    ),new FormTitle( 'Debitor' )),
                                    new FormGroup( array(
                                        new FormRow( array(
                                            new FormColumn(
                                                new TextField( 'Debtor[Owner]', 'Vorname Nachname', 'Inhaber', new PersonIcon()
                                                ), 6),
                                            new FormColumn(
                                                new TextField( 'Debtor[BankName]', 'Name der Bank', 'Name der Bank', new BuildingIcon()
                                                ), 6),
                                            new FormColumn(
                                                new TextField( 'Debtor[IBAN]', 'XXXX XXXX XXXX XXXX XXXX XX', 'IBAN', new BarCodeIcon()
                                                ), 4),
                                            new FormColumn(
                                                new TextField( 'Debtor[BIC]', 'XXXX XX XX XXX', 'BIC', new BarCodeIcon()
                                                ), 4),
                                            new FormColumn(
                                                new TextField( 'Debtor[CashSign]', 'Kassenzeichen', 'Kassenzeichen', new NameplateIcon()
                                                ), 4),
                                        ))
                                    ),new FormTitle( 'Bankdaten' )),
                                    new FormGroup( array(
                                        new FormRow( array(
                                            new FormColumn(
                                                new TextField( 'Debtor[Reference]', 'Referenz', 'Mandatsreferenz', new BarCodeIcon()
                                                ), 4),
                                            new FormColumn(
                                                new DatePicker( 'Debtor[ReferenceDate]', 'Datum', 'Erstellungsdatum', new TimeIcon()
                                                ), 4),
                                            new FormColumn(
                                                new SelectBox( 'Debtor[Commodity]', 'Leistung', array( 'Name' => $tblCommodity ) , new TimeIcon()
                                                ), 4),
                                        )),
                                    ),new FormTitle( 'Mandatsreferenz' ))
                                ), new SubmitPrimary( 'Hinzufügen' )), $Debtor, $Id )
                        ))
                    ))
                )),
                ( !empty( $tblDebtor ) ) ?
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new Form( array(
                                new FormGroup( array(
                                    new FormRow( array(
                                        new FormColumn( array(
                                            new TableData( $tblDebtor, null, array(
                                                'DebtorNumber' => 'Debitorennummer',
                                                'BankName' => 'Name der Bank',
                                                'IBAN' => 'IBAN',
                                                'BIC' => 'BIC',
                                                'Owner' => 'Inhaber'
                                            ))
                                        ))
                                    ))
                                ))
                            ))
                        ),12)
                    ))
                ), new LayoutTitle( 'Vorhandene Debitorennummer(n)' ) ) : null,
            ))
        );

        return $View;
    }

}