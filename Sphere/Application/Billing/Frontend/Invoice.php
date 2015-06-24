<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceItem;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ChevronLeftIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DisableIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EyeOpenIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MapMarkerIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MinusIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MoneyEuroIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuantityIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TagIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TransferIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Button\Structure\ButtonGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutAddress;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutAspect;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutPanel;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Invoice
 *
 * @package KREDA\Sphere\Application\Billing\Frontend
 */
class Invoice extends AbstractFrontend
{
    /**
     * @return Stage
     */
    public static function frontendInvoiceStatus()
    {

        $View = new Stage();
        $View->setTitle( 'Rechnungen' );
        $View->setDescription( 'Übersicht' );

        return $View;
    }

    /**
     * @return Stage
     */
    public static function frontendInvoiceList()
    {

        $View = new Stage();
        $View->setTitle( 'Rechnungen' );
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Zeigt alle vorhandenen Rechnungen an' );

        $tblInvoiceAll = Billing::serviceInvoice()->entityInvoiceAll();

        if (!empty( $tblInvoiceAll )) {
            array_walk( $tblInvoiceAll, function ( TblInvoice &$tblInvoice )
            {
                $paymentType = $tblInvoice->getServiceBillingBankingPaymentType();
                if ($paymentType)
                {
                    $tblInvoice->PaymentType = $paymentType->getName();
                }
                else
                {
                    $tblInvoice->PaymentType = "";
                }

                $tblInvoice->Person = $tblInvoice->getServiceManagementPerson()->getFullName();
                $tblInvoice->Debtor = $tblInvoice->getDebtorFullName();
                $tblInvoice->TotalPrice = Billing::serviceInvoice()->sumPriceItemAllStringByInvoice( $tblInvoice );
                $tblInvoice->Option = (new Primary( 'Anzeigen', '/Sphere/Billing/Invoice/Show',
                    new EyeOpenIcon(), array('Id' => $tblInvoice->getId())))->__toString();;
                if ($tblInvoice->getIsPaid())
                {
                    $tblInvoice->IsPaidString = "Bezahlt (manuell)";
                }
                else if (Billing::serviceBalance()->entityBalanceByInvoice($tblInvoice)
                    && (Billing::serviceBalance()->sumPriceItemByBalance(Billing::serviceBalance()->entityBalanceByInvoice($tblInvoice))
                            >= Billing::serviceInvoice()->sumPriceItemAllByInvoice($tblInvoice)))
                {
                    $tblInvoice->IsPaidString = "Bezahlt";
                }
                else
                {
                    $tblInvoice->IsPaidString = "";
                }
                if ($tblInvoice->getIsVoid())
                {
                    $tblInvoice->IsVoidString = "Storniert";
                }
                else
                {
                    $tblInvoice->IsVoidString = "";
//                    $tblInvoice->Option .= (new Danger( 'Stornieren', '/Sphere/Billing/Invoice/Cancel',
//                        new RemoveIcon(), array('Id' => $tblInvoice->getId())))->__toString();
                }
                if ($tblInvoice->getIsConfirmed())
                {
                    $tblInvoice->IsConfirmedString = "Bestätigt";
                }
                else
                {
                    $tblInvoice->IsConfirmedString = "";
                }
            });
        }

        $View->setContent(
            new TableData( $tblInvoiceAll, null,
                array(
                    'Number' => 'Nummer',
                    'InvoiceDate' => 'Rechnungsdatum',
                    'BasketName' => 'Warenkorb',
                    'Person' => 'Person',
                    'Debtor' => 'Debitor',
                    'DebtorNumber' => 'Debitoren-Nr',
                    'PaymentType' => 'Zahlungsart',
                    'TotalPrice' => 'Gesamtpreis',
                    'IsConfirmedString' => 'Bestätigt',
                    'IsPaidString' => 'Bezahlt',
                    'IsVoidString' => 'Storniert',
                    'Option' => 'Option'
                )
            )
        );

        return $View;
    }

    /**
     * @return Stage
     */
    public static function frontendInvoiceIsNotConfirmedList()
    {
        $View = new Stage();
        $View->setTitle( 'Rechnungen' );
        $View->setDescription( 'Freigeben' );
        $View->setMessage( 'Zeigt alle noch nicht freigegebenen Rechnungen an' );

        $tblInvoiceAllByIsConfirmedState = Billing::serviceInvoice()->entityInvoiceAllByIsConfirmedState(false);
        $tblInvoiceAllByIsVoid = Billing::serviceInvoice()->entityInvoiceAllByIsVoidState(true);

        if ($tblInvoiceAllByIsConfirmedState && $tblInvoiceAllByIsVoid)
        {
            $tblInvoiceAllByIsConfirmedState = array_udiff($tblInvoiceAllByIsConfirmedState, $tblInvoiceAllByIsVoid,
                function(TblInvoice $invoiceA, TblInvoice $invoiceB){
                    return $invoiceA->getId() - $invoiceB->getId();
                });
        }

        if (!empty( $tblInvoiceAllByIsConfirmedState )) {
            array_walk( $tblInvoiceAllByIsConfirmedState, function ( TblInvoice &$tblInvoice )
            {
                $paymentType = $tblInvoice->getServiceBillingBankingPaymentType();
                if ($paymentType)
                {
                    $tblInvoice->PaymentType = $paymentType->getName();
                }
                else
                {
                    $tblInvoice->PaymentType = "";
                }

                if ($tblInvoice->getIsPaymentDateModified())
                {
                    $tblInvoice->InvoiceDateString = new Warning( $tblInvoice->getInvoiceDate());
                    $tblInvoice->PaymentDateString = new Warning( $tblInvoice->getPaymentDate());
                }
                else
                {
                    $tblInvoice->InvoiceDateString = $tblInvoice->getInvoiceDate();
                    $tblInvoice->PaymentDateString = $tblInvoice->getPaymentDate();
                }

                $tblInvoice->Person = $tblInvoice->getServiceManagementPerson()->getFullName();
                $tblInvoice->Debtor = $tblInvoice->getDebtorFullName();
                $tblInvoice->TotalPrice = Billing::serviceInvoice()->sumPriceItemAllStringByInvoice( $tblInvoice );
                $tblInvoice->Option =
                    ( new Primary( 'Bearbeiten und Freigeben', '/Sphere/Billing/Invoice/IsNotConfirmed/Edit',
                        new EditIcon(), array(
                            'Id' => $tblInvoice->getId()
                        ) ) )->__toString();
            } );
        }

        $View->setContent(
            new TableData( $tblInvoiceAllByIsConfirmedState, null,
                array(
                    'Number' => 'Nummer',
                    'InvoiceDateString' => 'Rechnungsdatum',
                    'PaymentDateString' => 'Zahlungsdatum',
                    'BasketName' => 'Warenkorb',
                    'Person' => 'Person',
                    'Debtor' => 'Debitor',
                    'DebtorNumber' => 'Debitoren-Nr',
                    'PaymentType' => 'Zahlungsart',
                    'TotalPrice' => 'Gesamtpreis',
                    'Option' => 'Option'
                )
            )
        );

        return $View;
    }

    /**
     * @param $Id
     * @param $Data
     *
     * @return Stage
     */
    public static function frontendInvoiceEdit( $Id , $Data )
    {
        $View = new Stage();
        $View->setTitle( 'Rechnung' );
        $View->setDescription( 'Bearbeiten' );
        $View->setMessage(
            'Hier können Sie die Rechnung bearbeiten und freigeben. <br>
            <b>Hinweis:</b> Freigegebene Rechnung sind nicht mehr bearbeitbar.'
        );
        $View->addButton( new Primary( 'Geprüft und Freigeben', '/Sphere/Billing/Invoice/Confirm',
            new OkIcon(), array(
                'Id' => $Id
            )
        ) );
        $View->addButton( new Danger( 'Stornieren', '/Sphere/Billing/Invoice/Cancel',
            new RemoveIcon(), array(
                'Id' => $Id
            )
        ) );
        $View->addButton( new Primary( 'Zurück', '/Sphere/Billing/Invoice/IsNotConfirmed', new ChevronLeftIcon()));


        $tblInvoice = Billing::serviceInvoice()->entityInvoiceById( $Id );
        if ($tblInvoice->getIsConfirmed())
        {
            $View->setContent( new Warning( 'Die Rechnung wurde bereits bestätigt und freigegeben und kann nicht mehr bearbeitet werden' )
                .new Redirect( '/Sphere/Billing/Invoice', 2));
        }
        else
        {
            $tblInvoiceItemAll = Billing::serviceInvoice()->entityInvoiceItemAllByInvoice( $tblInvoice );
            if (!empty( $tblInvoiceItemAll )) {
                array_walk( $tblInvoiceItemAll, function ( TblInvoiceItem &$tblInvoiceItem, $index, TblInvoice $tblInvoice )
                {
                    if ($tblInvoice->getServiceBillingBankingPaymentType()->getId() == 1 ) //SEPA-Lastschrift
                    {
                        $tblCommodity = Billing::serviceCommodity()->entityCommodityByName($tblInvoiceItem->getCommodityName());
                        if ($tblCommodity)
                        {

                            $tblDebtor = Billing::serviceBanking()->entityDebtorByDebtorNumber( $tblInvoice->getDebtorNumber());
                            if ($tblDebtor)
                            {
                                if(Billing::serviceBanking()->entityReferenceByDebtorAndCommodity($tblDebtor, $tblCommodity))
                                {
                                    $tblInvoiceItem->Status = new Success(
                                        'Mandatsreferenz', new OkIcon()
                                    );
                                }
                                else
                                {
                                    $tblInvoiceItem->Status = new Warning(
                                        'keine Mandatsreferenz', new DisableIcon()
                                    );
                                }
                            }
                            else
                            {
                                $tblInvoiceItem->Status = new \KREDA\Sphere\Client\Frontend\Message\Type\Danger(
                                    'Debitor nicht gefunden', new DisableIcon()
                                );
                            }
                        }
                        else
                        {
                            $tblInvoiceItem->Status = new \KREDA\Sphere\Client\Frontend\Message\Type\Danger(
                                'Leistung nicht gefunden', new DisableIcon()
                            );
                        }
                    }
                    else
                    {
                        $tblInvoiceItem->Status="";
                    }

                    $tblInvoiceItem->TotalPriceString = $tblInvoiceItem->getTotalPriceString();
                    $tblInvoiceItem->QuantityString = str_replace('.',',', $tblInvoiceItem->getItemQuantity());
                    $tblInvoiceItem->PriceString = $tblInvoiceItem->getPriceString();
                    $tblInvoiceItem->Option =
                        ( new Primary( 'Bearbeiten', '/Sphere/Billing/Invoice/IsNotConfirmed/Item/Edit',
                            new EditIcon(), array(
                                'Id' => $tblInvoiceItem->getId()
                            ) ) )->__toString().
                        ( new Danger( 'Entfernen',
                            '/Sphere/Billing/Invoice/IsNotConfirmed/Item/Remove',
                            new MinusIcon(), array(
                                'Id' => $tblInvoiceItem->getId()
                            ) ) )->__toString();
                }, $tblInvoice );
            }

            $View->setContent(
                new Layout( array(
                    new LayoutGroup( array(
                        new LayoutRow( array(
                            new LayoutColumn(
                                new LayoutPanel( 'Rechnungsnummer' , $tblInvoice->getNumber(), LayoutPanel::PANEL_TYPE_PRIMARY ), 3
                            ),
                            new LayoutColumn(
                                new LayoutPanel( 'Warenkorb' , $tblInvoice->getBasketName(), LayoutPanel::PANEL_TYPE_DEFAULT ), 3
                            ),
                            new LayoutColumn(
                                new LayoutPanel( 'Rechnungsdatum' , $tblInvoice->getInvoiceDate(),
                                    $tblInvoice->getIsPaymentDateModified() ? LayoutPanel::PANEL_TYPE_WARNING : LayoutPanel::PANEL_TYPE_DEFAULT), 3
                            ),
                            new LayoutColumn(
                                new LayoutPanel( 'Zahlungsdatum' , $tblInvoice->getPaymentDate(),
                                    $tblInvoice->getIsPaymentDateModified() ? LayoutPanel::PANEL_TYPE_WARNING : LayoutPanel::PANEL_TYPE_DEFAULT), 3
                            ),
                        ) ),
                        new LayoutRow(
                            new LayoutColumn(
                                new LayoutAspect( 'Empfänger' )
                            )
                        ),
                        new LayoutRow( array(
                            new LayoutColumn(
                                new LayoutPanel( 'Debitor' , $tblInvoice->getDebtorFullName() ), 3
                            ),
                            new LayoutColumn(
                                new LayoutPanel( 'Debitorennummer' , $tblInvoice->getDebtorNumber() ), 3
                            ),
                            new LayoutColumn(
                                new LayoutPanel( 'Person' , $tblInvoice->getServiceManagementPerson()->getFullName() ), 3
                            )
                        ) ),
                        new LayoutRow( array(
                            new LayoutColumn(
                                ($tblInvoice->getServiceManagementAddress()
                                    ? new LayoutPanel(
                                        new MapMarkerIcon() . ' Rechnungsadresse' ,
                                        new LayoutAddress( $tblInvoice->getServiceManagementAddress()),
                                            LayoutPanel::PANEL_TYPE_DEFAULT,
                                            (($tblDebtor = Billing::serviceBanking()->entityDebtorByDebtorNumber(
                                                $tblInvoice->getDebtorNumber()))
                                                && count(Management::servicePerson()->entityAddressAllByPerson(
                                                    $tblDebtor->getServiceManagementPerson())) > 1
                                                    ? new Primary( 'Bearbeiten', '/Sphere/Billing/Invoice/IsNotConfirmed/Address/Select',
                                                        new EditIcon(),
                                                        array(
                                                            'Id' => $tblInvoice->getId(),
                                                            'AddressId' => $tblInvoice->getServiceManagementAddress()->getId()
                                                        )
                                                    )
                                                    : null
                                        )
                                    )
                                    : new \KREDA\Sphere\Client\Frontend\Message\Type\Warning(
                                        'Keine Rechnungsadresse verfügbar', new DisableIcon()
                                    )
                                ), 3
                            ),
                            new LayoutColumn(
                                new LayoutPanel(
                                    'Zahlungsart' ,
                                    $tblInvoice->getServiceBillingBankingPaymentType()->getName(),
                                    LayoutPanel::PANEL_TYPE_DEFAULT,
                                    new Primary( 'Bearbeiten', '/Sphere/Billing/Invoice/IsNotConfirmed/Payment/Type/Select',
                                        new EditIcon(),
                                        array(
                                            'Id' => $tblInvoice->getId()
                                        )
                                    )
                                ), 3
                            ),
                        ) ),
                        new LayoutRow(
                            new LayoutColumn(
                                new LayoutAspect( 'Betrag' )
                            )
                        ),
                        new LayoutRow( array(
                            new LayoutColumn(
                                new LayoutPanel( 'Rechnungsbetrag' , Billing::serviceInvoice()->sumPriceItemAllStringByInvoice( $tblInvoice ) ), 3
                            )
                        ) )
                    ), new LayoutTitle( 'Kopf' )),
                    new LayoutGroup( array(
                        new LayoutRow( array(
                            new LayoutColumn( array(
                                    new TableData( $tblInvoiceItemAll, null,
                                        array(
                                            'CommodityName' => 'Leistung',
                                            'ItemName' => 'Artikel',
                                            'PriceString' => 'Preis',
                                            'QuantityString' => 'Menge',
                                            'TotalPriceString' => 'Gesamtpreis',
                                            'Status' => 'Status',
                                            'Option' => 'Option'
                                        )
                                    )
                                )
                            )
                        ) ),
                    ), new LayoutTitle( 'Positionen' ) )
                ) )
            );
        }

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoiceShow( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Rechnung' );
        $View->setDescription( 'Anzeigen' );
        $View->addButton( new Primary( 'Zurück', '/Sphere/Billing/Invoice', new ChevronLeftIcon()));

        $tblInvoice = Billing::serviceInvoice()->entityInvoiceById( $Id );

        if ($tblInvoice->getIsVoid())
        {
            $View->setMessage(new \KREDA\Sphere\Client\Frontend\Message\Type\Danger("Diese Rechnung wurde storniert"));
        }

        $tblInvoiceItemAll = Billing::serviceInvoice()->entityInvoiceItemAllByInvoice( $tblInvoice );
        if (!empty( $tblInvoiceItemAll )) {
            array_walk( $tblInvoiceItemAll, function ( TblInvoiceItem &$tblInvoiceItem, $index, TblInvoice $tblInvoice )
            {
                if ($tblInvoice->getServiceBillingBankingPaymentType()->getId() == 1 ) //SEPA-Lastschrift
                {
                    $tblCommodity = Billing::serviceCommodity()->entityCommodityByName($tblInvoiceItem->getCommodityName());
                    if ($tblCommodity)
                    {

                        $tblDebtor = Billing::serviceBanking()->entityDebtorByDebtorNumber( $tblInvoice->getDebtorNumber());
                        if ($tblDebtor)
                        {
                            if(Billing::serviceBanking()->entityReferenceByDebtorAndCommodity($tblDebtor, $tblCommodity))
                            {
                                $tblInvoiceItem->Status = new Success(
                                    'Mandatsreferenz', new OkIcon()
                                );
                            }
                            else
                            {
                                $tblInvoiceItem->Status = new Warning(
                                    'keine Mandatsreferenz', new DisableIcon()
                                );
                            }
                        }
                        else
                        {
                            $tblInvoiceItem->Status = new \KREDA\Sphere\Client\Frontend\Message\Type\Danger(
                                'Debitor nicht gefunden', new DisableIcon()
                            );
                        }
                    }
                    else
                    {
                        $tblInvoiceItem->Status = new \KREDA\Sphere\Client\Frontend\Message\Type\Danger(
                            'Leistung nicht gefunden', new DisableIcon()
                        );
                    }
                }
                else
                {
                    $tblInvoiceItem->Status="";
                }

                $tblInvoiceItem->TotalPriceString = $tblInvoiceItem->getTotalPriceString();
                $tblInvoiceItem->QuantityString = str_replace('.',',', $tblInvoiceItem->getItemQuantity());
                $tblInvoiceItem->PriceString = $tblInvoiceItem->getPriceString();
            }, $tblInvoice );
        }


        $View->setContent(
            new Layout( array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn(
                            new LayoutPanel( 'Rechnungsnummer' , $tblInvoice->getNumber(), LayoutPanel::PANEL_TYPE_PRIMARY ), 3
                        ),
                        new LayoutColumn(
                            new LayoutPanel( 'Warenkorb' , $tblInvoice->getBasketName(), LayoutPanel::PANEL_TYPE_DEFAULT ), 3
                        ),
                        new LayoutColumn(
                            new LayoutPanel( 'Rechnungsdatum' , $tblInvoice->getInvoiceDate(),
                                $tblInvoice->getIsPaymentDateModified() ? LayoutPanel::PANEL_TYPE_WARNING : LayoutPanel::PANEL_TYPE_DEFAULT), 3
                        ),
                        new LayoutColumn(
                            new LayoutPanel( 'Zahlungsdatum' , $tblInvoice->getPaymentDate(),
                                $tblInvoice->getIsPaymentDateModified() ? LayoutPanel::PANEL_TYPE_WARNING : LayoutPanel::PANEL_TYPE_DEFAULT), 3
                        ),
                    ) ),
                    new LayoutRow(
                        new LayoutColumn(
                            new LayoutAspect( 'Empfänger' )
                        )
                    ),
                    new LayoutRow( array(
                        new LayoutColumn(
                            new LayoutPanel( 'Debitor' , $tblInvoice->getDebtorFullName() ), 3
                        ),
                        new LayoutColumn(
                            new LayoutPanel( 'Debitorennummer' , $tblInvoice->getDebtorNumber() ), 3
                        ),
                        new LayoutColumn(
                            new LayoutPanel( 'Person' , $tblInvoice->getServiceManagementPerson()->getFullName() ), 3
                        )
                    ) ),
                    new LayoutRow( array(
                        new LayoutColumn(
                            ($tblInvoice->getServiceManagementAddress()
                                ? new LayoutPanel(
                                    new MapMarkerIcon() . ' Rechnungsadresse' ,
                                    new LayoutAddress( $tblInvoice->getServiceManagementAddress()),
                                    LayoutPanel::PANEL_TYPE_DEFAULT
                                )
                                : new \KREDA\Sphere\Client\Frontend\Message\Type\Warning(
                                    'Keine Rechnungsadresse verfügbar', new DisableIcon()
                                )
                            ), 3
                        ),
                        new LayoutColumn(
                            new LayoutPanel(
                                'Zahlungsart' ,
                                $tblInvoice->getServiceBillingBankingPaymentType()->getName(),
                                LayoutPanel::PANEL_TYPE_DEFAULT
                            ), 3
                        ),
                    ) ),
                    new LayoutRow(
                        new LayoutColumn(
                            new LayoutAspect( 'Betrag' )
                        )
                    ),
                    new LayoutRow( array(
                        new LayoutColumn(
                            new LayoutPanel( 'Rechnungsbetrag' , Billing::serviceInvoice()->sumPriceItemAllStringByInvoice( $tblInvoice ) ), 3
                        ),
                        new LayoutColumn(
                            $tblInvoice->getIsConfirmed() ?
                                ($tblInvoice->getIsPaid()
                                    ?  new Success("Bezahlt")
                                    :   (round(Billing::serviceBalance()->sumPriceItemByBalance(Billing::serviceBalance()->entityBalanceByInvoice( $tblInvoice )),2)
                                        >= round(Billing::serviceInvoice()->sumPriceItemAllByInvoice( $tblInvoice),2)
                                            ?  new LayoutPanel( 'Bezahlbetrag' , Billing::serviceBalance()->sumPriceItemStringByBalance(
                                                    Billing::serviceBalance()->entityBalanceByInvoice( $tblInvoice ) ), LayoutPanel::PANEL_TYPE_SUCCESS )
                                            :  new LayoutPanel( 'Bezahlbetrag' , Billing::serviceBalance()->sumPriceItemStringByBalance(
                                                    Billing::serviceBalance()->entityBalanceByInvoice( $tblInvoice ) ), LayoutPanel::PANEL_TYPE_DANGER )))
                                : new \KREDA\Sphere\Client\Frontend\Text\Type\Primary("")
                                , 3)
                    ) ),
                ), new LayoutTitle( 'Kopf' )),
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new TableData( $tblInvoiceItemAll, null,
                                    array(
                                        'CommodityName' => 'Leistung',
                                        'ItemName' => 'Artikel',
                                        'PriceString' => 'Preis',
                                        'QuantityString' => 'Menge',
                                        'TotalPriceString' => 'Gesamtpreis',
                                        'Status' => 'Status'
                                    )
                                )
                            )
                        )
                    ) ),
                ), new LayoutTitle( 'Positionen' ) )
            ) )
        );

        return $View;
    }

    /**
     * @param $Id
     * @param $Data
     *
     * @return Stage
     */
    public static function frontendInvoiceConfirm( $Id, $Data )
    {
        $View = new Stage();
        $View->setTitle( 'Rechnung' );
        $View->setDescription( 'Freigeben' );

        $tblInvoice = Billing::serviceInvoice()->entityInvoiceById( $Id );
        $View->setContent( Billing::serviceInvoice()->executeConfirmInvoice( $tblInvoice, $Data ) );

        return $View;
    }


    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoiceCancel( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Rechnung' );
        $View->setDescription( 'Stornieren' );

        $tblInvoice = Billing::serviceInvoice()->entityInvoiceById( $Id );
        $View->setContent( Billing::serviceInvoice()->executeCancelInvoice( $tblInvoice ) );

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoiceAddressSelect( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Rechnung' );
        $View->setDescription( 'Rechnungsadresse Auswählen' );
        $View->addButton( new Primary('Zurück', '/Sphere/Billing/Invoice/IsNotConfirmed/Edit', new ChevronLeftIcon(),
            array('Id' => $Id)
        ));

        $tblInvoice = Billing::serviceInvoice()->entityInvoiceById( $Id );
        $tblAddressAll = Management::servicePerson()->entityAddressAllByPerson(
            Billing::serviceBanking()->entityDebtorByDebtorNumber($tblInvoice->getDebtorNumber())->getServiceManagementPerson());

        $layoutGroup = self::layoutAddress( $tblAddressAll, $tblInvoice->getServiceManagementAddress(), $tblInvoice );

        $View->setContent(
            new Layout(array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                         new LayoutColumn( array(
                             new LayoutPanel('Rechnungsnummer', $tblInvoice->getNumber(), LayoutPanel::PANEL_TYPE_SUCCESS )
                         ),3),
                         new LayoutColumn( array(
                             new LayoutPanel('Empfänger', $tblInvoice->getDebtorFullName(), LayoutPanel::PANEL_TYPE_SUCCESS )
                         ),3)
                    ))
                ))
            ))
            . $layoutGroup
        );

        return $View;
    }

    private static function layoutAddress($tblAddressList, TblAddress $invoiceAddress, TblInvoice $tblInvoice)
    {
        if (!empty($tblAddressList))
        {
            /** @var TblAddress[] $tblAddressList */
            foreach ($tblAddressList as &$tblAddress)
            {
                if ($invoiceAddress != null && $invoiceAddress->getId() === $tblAddress->getId())
                {
                    $AddressType = new MapMarkerIcon().' Rechnungsadresse';
                    $PanelType = LayoutPanel::PANEL_TYPE_SUCCESS;
                }
                else
                {
                    $AddressType = new MapMarkerIcon().' Adresse';
                    $PanelType = LayoutPanel::PANEL_TYPE_DEFAULT;
                }

                $tblAddress = new LayoutColumn(
                    new LayoutPanel(
                        $AddressType, new LayoutAddress( $tblAddress ), $PanelType,
                        new Primary( 'Auswählen', '/Sphere/Billing/Invoice/IsNotConfirmed/Address/Change',
                            new OkIcon(),
                            array(
                                'Id' => $tblInvoice->getId(),
                                'AddressId' => $tblAddress->getId()
                            )
                        )
                    ), 3
                );
            }
        }
        else
        {
            $tblAddressList = array(
                new LayoutColumn(
                    new Warning( 'Keine Adressen hinterlegt', new WarningIcon() )
                )
            );
        }

        return new Layout(
            new LayoutGroup( new LayoutRow( $tblAddressList ), new LayoutTitle( 'Adressen' ) )
        );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoicePaymentTypeSelect( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Rechnung' );
        $View->setDescription( 'Zahlungsart Auswählen' );
        $View->addButton( new Primary('Zurück', '/Sphere/Billing/Invoice/IsNotConfirmed/Edit', new ChevronLeftIcon(),
            array('Id' => $Id)
        ));

        $tblInvoice = Billing::serviceInvoice()->entityInvoiceById( $Id );
        $tblPaymentTypeList = Billing::serviceBanking()->entityPaymentTypeAll();

        if($tblPaymentTypeList)
        {
            foreach ($tblPaymentTypeList as &$tblPaymentType)
            {
                $tblPaymentType = new LayoutColumn(
                    new LayoutPanel(
                        'Zahlungsart',
                        $tblPaymentType->getName(),
                        $tblPaymentType->getId() === $tblInvoice->getServiceBillingBankingPaymentType()->getId() ?
                            LayoutPanel::PANEL_TYPE_SUCCESS :
                            LayoutPanel::PANEL_TYPE_DEFAULT,
                        new Primary( 'Auswählen', '/Sphere/Billing/Invoice/IsNotConfirmed/Payment/Type/Change',
                            new OkIcon(),
                            array(
                                'Id' => $tblInvoice->getId(),
                                'PaymentTypeId' => $tblPaymentType->getId()
                            )
                        )
                    ), 3
                );
            }
        }
        $View->setContent(
            new Layout(array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutPanel('Rechnungsnummer', $tblInvoice->getNumber(), LayoutPanel::PANEL_TYPE_SUCCESS )
                        ),3),
                        new LayoutColumn( array(
                            new LayoutPanel('Empfänger', $tblInvoice->getDebtorFullName(), LayoutPanel::PANEL_TYPE_SUCCESS )
                        ),3)
                    ))
                )),
                new LayoutGroup(
                    new LayoutRow(
                        $tblPaymentTypeList
                    ), new LayoutTitle( 'Zahlungsarten')
                )
            ))
        );

        return $View;
    }

    /**
     * @param $Id
     * @param $AddressId
     *
     * @return Stage
     */
    public static function frontendInvoiceAddressChange( $Id, $AddressId )
    {
        $View = new Stage();
        $View->setTitle( 'Rechnung' );
        $View->setDescription( 'Rechnungsadresse Ändern' );

        $tblInvoice = Billing::serviceInvoice()->entityInvoiceById( $Id );
        $tblAddress = Management::serviceAddress()->entityAddressById( $AddressId );
        $View->setContent( Billing::serviceInvoice()->executeChangeInvoiceAddress( $tblInvoice, $tblAddress ) );

        return $View;
    }

    /**
     * @param $Id
     * @param $PaymentTypeId
     *
     * @return Stage
     */
    public static function frontendInvoicePaymentTypeChange( $Id, $PaymentTypeId )
    {
        $View = new Stage();
        $View->setTitle( 'Rechnung' );
        $View->setDescription( 'Zahlungsart Ändern' );

        $tblInvoice = Billing::serviceInvoice()->entityInvoiceById( $Id );
        $tblPaymentType = Billing::serviceBanking()->entityPaymentTypeById( $PaymentTypeId );
        $View->setContent( Billing::serviceInvoice()->executeChangeInvoicePaymentType( $tblInvoice, $tblPaymentType ) );

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoicePay( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Rechnung' );
        $View->setDescription( 'Bezahlen' );

        $tblInvoice = Billing::serviceInvoice()->entityInvoiceById( $Id );
        $View->setContent( Billing::serviceInvoice()->executePayInvoice( $tblInvoice ) );

        return $View;
    }

    /**
     * @param $Id
     * @param $InvoiceItem
     *
     * @return Stage
     */
    public static function frontendInvoiceItemEdit( $Id, $InvoiceItem )
    {
        $View = new Stage();
        $View->setTitle( 'Rechnung' );
        $View->setDescription( 'Artikel Bearbeiten' );
        $View->addButton( new Primary('Zurück', '/Sphere/Billing/Invoice/IsNotConfirmed/Edit', new ChevronLeftIcon(),
            array('Id' => $Id)
        ));

        if (empty( $Id )) {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblInvoiceItem = Billing::serviceInvoice()->entityInvoiceItemById( $Id );
            if (empty( $tblInvoiceItem )) {
                $View->setContent( new Warning( 'Der Artikel konnte nicht abgerufen werden' ) );
            } else {

                $Global = self::extensionSuperGlobal();
                if (!isset( $Global->POST['InvoiceItems']) )
                {
                    $Global->POST['InvoiceItem']['Price'] = str_replace( '.', ',', $tblInvoiceItem->getItemPrice() );
                    $Global->POST['InvoiceItem']['Quantity'] = str_replace( '.', ',', $tblInvoiceItem->getItemQuantity() );
                    $Global->savePost();
                }

                $View->setContent(
                    new Layout(array(
                        new LayoutGroup( array(
                            new LayoutRow( array(
                                new LayoutColumn(
                                    new LayoutPanel('Leistung-Name', $tblInvoiceItem->getCommodityName()
                                        , LayoutPanel::PANEL_TYPE_SUCCESS ), 3
                                ),
                                new LayoutColumn(
                                    new LayoutPanel('Artikel-Name', $tblInvoiceItem->getItemName()
                                        , LayoutPanel::PANEL_TYPE_SUCCESS ), 3
                                ),
                                new LayoutColumn(
                                    new LayoutPanel('Artikel-Beschreibung', $tblInvoiceItem->getItemDescription()
                                        , LayoutPanel::PANEL_TYPE_SUCCESS ), 6
                                )
                            ) ),
                        )),
                        new LayoutGroup( array(
                            new LayoutRow( array(
                                new LayoutColumn( array(
                                        Billing::serviceInvoice()->executeEditInvoiceItem(
                                            new Form( array(
                                                    new FormGroup( array(
                                                        new FormRow( array(
                                                            new FormColumn(
                                                                new TextField( 'InvoiceItem[Price]', 'Preis in €', 'Preis', new MoneyEuroIcon()
                                                                ), 6 ),
                                                            new FormColumn(
                                                                new TextField( 'InvoiceItem[Quantity]', 'Menge', 'Menge', new QuantityIcon()
                                                                ), 6 )
                                                        ) )
                                                    ) )
                                                ), new SubmitPrimary( 'Änderungen speichern' )
                                            ), $tblInvoiceItem, $InvoiceItem
                                        )
                                    )
                                )
                            ) )
                        ) )
                    ) )
                );
            }
        }

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoiceItemRemove( $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Rechnung' );
        $View->setDescription( 'Artikel Entfernen' );

        $tblInvoiceItem = Billing::serviceInvoice()->entityInvoiceItemById( $Id );
        $View->setContent( Billing::serviceInvoice()->executeRemoveInvoiceItem( $tblInvoiceItem ) );

        return $View;
    }
}
