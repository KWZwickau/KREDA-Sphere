<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceItem;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
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
            array_walk( $tblInvoiceAll, function ( TblInvoice &$tblInvoice ) {
                $tblInvoice->Student = $tblInvoice->getServiceManagementPerson()->getFullName();
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
                    $tblInvoice->Option .= (new Danger( 'Stornieren', '/Sphere/Billing/Invoice/Cancel',
                        new RemoveIcon(), array('Id' => $tblInvoice->getId())))->__toString();
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
                    'Student' => 'Schüler',
                    'Debtor' => 'Debitor',
                    'DebtorNumber' => 'Debitorennummer',
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
        $View->setMessage( 'Zeigt alle noch nicht bestätigten Rechnungen an' );

        $tblInvoiceAllByIsConfirmedState = Billing::serviceInvoice()->entityInvoiceAllByIsConfirmedState(false);

        if (!empty( $tblInvoiceAllByIsConfirmedState )) {
            array_walk( $tblInvoiceAllByIsConfirmedState, function ( TblInvoice &$tblInvoice ) {
                if ($tblInvoice->getIsPaymentDateModified())
                {
                    $tblInvoice->InvoiceDateString = new \KREDA\Sphere\Client\Frontend\Text\Type\Warning( $tblInvoice->getInvoiceDate());
                    $tblInvoice->PaymentDateString = new \KREDA\Sphere\Client\Frontend\Text\Type\Warning( $tblInvoice->getPaymentDate());
                }
                else
                {
                    $tblInvoice->InvoiceDateString = $tblInvoice->getInvoiceDate();
                    $tblInvoice->PaymentDateString = $tblInvoice->getPaymentDate();
                }
                $tblInvoice->Student = $tblInvoice->getServiceManagementPerson()->getFullName();
                $tblInvoice->Debtor = $tblInvoice->getDebtorFullName();
                $tblInvoice->TotalPrice = Billing::serviceInvoice()->sumPriceItemAllStringByInvoice( $tblInvoice );
                $tblInvoice->Option =
                    ( new Primary( 'Bearbeiten und Freigeben', '/Sphere/Billing/Invoice/Edit',
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
                    'Student' => 'Schüler',
                    'Debtor' => 'Debitor',
                    'DebtorNumber' => 'Debitorennummer',
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
        $View->setMessage( 'Hier können Sie die Rechnung bearbeiten' );
        $View->addButton( new Primary( 'Geprüft und Freigeben', '/Sphere/Billing/Invoice/Confirm',
            new OkIcon(), array(
                'Id' => $Id
            )
        ) );
        $View->addButton( new Danger( 'Stornieren', '/Sphere/Billing/Invoice/Cancel',
            new RemoveIcon(), array(
                'Id' => $Id
            ) ) );

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
                array_walk( $tblInvoiceItemAll, function ( TblInvoiceItem &$tblInvoiceItem ) {
                    $tblInvoiceItem->Option =
                        ( new Primary( 'Bearbeiten', '/Sphere/Billing/Invoice/Item/Edit',
                            new EditIcon(), array(
                                'Id' => $tblInvoiceItem->getId()
                            ) ) )->__toString().
                        ( new Danger( 'Entfernen',
                            '/Sphere/Billing/Invoice/Item/Remove',
                            new MinusIcon(), array(
                                'Id' => $tblInvoiceItem->getId()
                            ) ) )->__toString();
                } );
            }

            $View->setContent(
                new Layout( array(
                    new LayoutGroup( array(
                        new LayoutRow( array(
                            new LayoutColumn(
                                new LayoutPanel( 'Rechnungsnummer' , $tblInvoice->getNumber(), LayoutPanel::PANEL_TYPE_PRIMARY ), 4
                            ),
                            new LayoutColumn(
                                new LayoutPanel( 'Rechnungsdatum' , $tblInvoice->getInvoiceDate(),
                                    $tblInvoice->getIsPaymentDateModified() ? LayoutPanel::PANEL_TYPE_WARNING : LayoutPanel::PANEL_TYPE_DEFAULT), 4
                            ),
                            new LayoutColumn(
                                new LayoutPanel( 'Zahlungsdatum' , $tblInvoice->getPaymentDate(),
                                    $tblInvoice->getIsPaymentDateModified() ? LayoutPanel::PANEL_TYPE_WARNING : LayoutPanel::PANEL_TYPE_DEFAULT), 4
                            ),
                        ) ),
                        new LayoutRow(
                            new LayoutColumn(
                                new LayoutAspect( 'Empfänger' )
                            )
                        ),
                        new LayoutRow( array(
                            new LayoutColumn(
                                new LayoutPanel( 'Debitor' , $tblInvoice->getDebtorFullName() ), 4
                            ),
                            new LayoutColumn(
                                new LayoutPanel( 'Debitorennummer' , $tblInvoice->getDebtorNumber() ), 4
                            ),
                            new LayoutColumn(
                                new LayoutPanel( 'Schüler' , $tblInvoice->getServiceManagementPerson()->getFullName() ), 4
                            )
                        ) ),
                        new LayoutRow(
                            new LayoutColumn(
                                new LayoutAspect( 'Adresse' )
                            )
                        ),
                        new LayoutRow(
                            new LayoutColumn(
                                $tblInvoice->getServiceManagementAddress()
                                    ? new LayoutPanel(
                                        new MapMarkerIcon() . 'Rechnungsadresse' ,
                                        new LayoutAddress( $tblInvoice->getServiceManagementAddress()),
                                        LayoutPanel::PANEL_TYPE_DEFAULT,
                                        (($tblDebtor = Billing::serviceBanking()->entityDebtorByDebtorNumber(
                                            $tblInvoice->getDebtorNumber()))
                                            && count(Management::servicePerson()->entityAddressAllByPerson(
                                                $tblDebtor->getServiceManagementPerson())) > 1
                                                ? new Primary( 'Bearbeiten', '/Sphere/Billing/Invoice/Address/Select',
                                                    new EditIcon(),
                                                    array(
                                                        'Id' => $tblInvoice->getId(),
                                                        'AddressId' => $tblInvoice->getServiceManagementAddress()->getId()
                                                    )
                                                )
                                                : null
                                        ), 4
                                    )
                                    : new \KREDA\Sphere\Client\Frontend\Message\Type\Warning("Keine Rechnungsadresse verfügbar")
                            )
                        ),
                        new LayoutRow(
                            new LayoutColumn(
                                new LayoutAspect( 'Betrag' )
                            )
                        ),
                        new LayoutRow( array(
                            new LayoutColumn(
                                new LayoutPanel( 'Rechnungsbetrag' , Billing::serviceInvoice()->sumPriceItemAllStringByInvoice( $tblInvoice ) ), 4
                            )
                        ) )
                    ), new LayoutTitle( 'Kopf' )),
                    new LayoutGroup( array(
                        new LayoutRow( array(
                            new LayoutColumn( array(
                                    new TableData( $tblInvoiceItemAll, null,
                                        array(
                                            'CommodityName' => 'Leistung',
                                            'ItemName'      => 'Artikel',
                                            'ItemPrice'         => 'Preis',
                                            'ItemQuantity'      => 'Menge',
                                            'Option'        => 'Option'
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

        $tblInvoice = Billing::serviceInvoice()->entityInvoiceById( $Id );

        $tblInvoiceItemAll = Billing::serviceInvoice()->entityInvoiceItemAllByInvoice( $tblInvoice );

        if ($tblInvoice->getIsVoid())
        {
            $View->setMessage(new \KREDA\Sphere\Client\Frontend\Message\Type\Danger("Diese Rechnung wurde storniert"));
        }

        $View->setContent(
            new Layout( array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn(
                            new LayoutPanel( 'Rechnungsnummer' , $tblInvoice->getNumber(), LayoutPanel::PANEL_TYPE_PRIMARY ), 4
                        ),
                        new LayoutColumn(
                            new LayoutPanel( 'Rechnungsdatum' , $tblInvoice->getInvoiceDate() ), 4
                        ),
                        new LayoutColumn(
                            new LayoutPanel( 'Zahlungsdatum' , $tblInvoice->getPaymentDate() ), 4
                        ),
                    ) ),
                    new LayoutRow(
                        new LayoutColumn(
                            new LayoutAspect( 'Empfänger' )
                        )
                    ),
                    new LayoutRow( array(
                        new LayoutColumn(
                            new LayoutPanel( 'Debitor' , $tblInvoice->getDebtorFullName() ), 4
                        ),
                        new LayoutColumn(
                            new LayoutPanel( 'Debitorennummer' , $tblInvoice->getDebtorNumber() ), 4
                        ),
                        new LayoutColumn(
                            new LayoutPanel( 'Schüler' , $tblInvoice->getServiceManagementPerson()->getFullName() ), 4
                        )
                    ) ),
                    new LayoutRow(
                        new LayoutColumn(
                            new LayoutAspect( 'Adresse' )
                        )
                    ),
                    new LayoutRow(
                        new LayoutColumn(
                            $tblInvoice->getServiceManagementAddress()
                                ? new LayoutPanel(
                                    new MapMarkerIcon() . 'Rechnungsadresse' ,
                                    new LayoutAddress( $tblInvoice->getServiceManagementAddress()),
                                    LayoutPanel::PANEL_TYPE_DEFAULT,
                                    null
                                )
                                : new \KREDA\Sphere\Client\Frontend\Message\Type\Warning("Keine Rechnungsadresse verfügbar")
                        )
                    ),
                    new LayoutRow(
                        new LayoutColumn(
                            new LayoutAspect( 'Betrag' )
                        )
                    ),
                    new LayoutRow( array(
                        new LayoutColumn(
                            new LayoutPanel( 'Rechnungsbetrag' , Billing::serviceInvoice()->sumPriceItemAllStringByInvoice( $tblInvoice ) ), 4
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
                                , 4)
                    ) ),
                ), new LayoutTitle( 'Kopf' )),
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new TableData( $tblInvoiceItemAll, null,
                                    array(
                                        'CommodityName' => 'Leistung',
                                        'ItemName'      => 'Artikel',
                                        'ItemPrice'         => 'Preis',
                                        'ItemQuantity'      => 'Menge'
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
        $View->setDescription( 'Bestätigen' );

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
        $View->setTitle( 'Rechnungsadresse' );
        $View->setDescription( 'Auswählen' );

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
                    $AddressType = new MapMarkerIcon().' Lieferadresse';
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
                        new Primary( 'Auswählen', '/Sphere/Billing/Invoice/Address/Change',
                            new OkIcon(),
                            array(
                                'Id' => $tblInvoice->getId(),
                                'AddressId' => $tblAddress->getId()
                            )
                        )
                    ), 4
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
     * @param $AddressId
     *
     * @return Stage
     */
    public static function frontendInvoiceAddressChange( $Id, $AddressId )
    {
        $View = new Stage();
        $View->setTitle( 'Rechnungsadresse' );
        $View->setDescription( 'Ändern' );

        $tblInvoice = Billing::serviceInvoice()->entityInvoiceById( $Id );
        $tblAddress = Management::serviceAddress()->entityAddressById( $AddressId );
        $View->setContent( Billing::serviceInvoice()->executeChangeInvoiceAddress( $tblInvoice, $tblAddress ) );

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
        $View->setTitle( 'Artikel' );
        $View->setDescription( 'Bearbeiten' );

        if (empty( $Id )) {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblInvoiceItem = Billing::serviceInvoice()->entityInvoiceItemById( $Id );
            if (empty( $tblInvoiceItem )) {
                $View->setContent( new Warning( 'Der Artikel konnte nicht abgerufen werden' ) );
            } else {

                $Global = self::extensionSuperGlobal();
                $Global->POST['InvoiceItem']['Price'] = str_replace( '.', ',', $tblInvoiceItem->getItemPrice() );
                $Global->POST['InvoiceItem']['Quantity'] = str_replace( '.', ',', $tblInvoiceItem->getItemQuantity() );
                $Global->savePost();

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
        $View->setTitle( 'Artikel' );
        $View->setDescription( 'Entfernen' );

        $tblInvoiceItem = Billing::serviceInvoice()->entityInvoiceItemById( $Id );
        $View->setContent( Billing::serviceInvoice()->executeRemoveInvoiceItem( $tblInvoiceItem ) );

        return $View;
    }
}
