<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceItem;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MinusIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
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
                if (!($tblInvoice->getIsConfirmed()))
                {
                    $tblInvoice->Option =
                        ( new Primary( 'Bearbeiten', '/Sphere/Billing/Invoice/Edit',
                            new EditIcon(), array(
                                'Id' => $tblInvoice->getId()
                            ) ) )->__toString().
                        ( new Danger( 'Stornieren', '/Sphere/Billing/Invoice/Cancel',
                            new RemoveIcon(), array(
                                'Id' => $tblInvoice->getId()
                            ) ) )->__toString();
                }
                else
                {
                    $tblInvoice->Option =
                        ( new Danger( 'Stornieren', '/Sphere/Billing/Invoice/Cancel',
                            new RemoveIcon(), array(
                                'Id' => $tblInvoice->getId()
                            ) ) )->__toString();
                }
            });
        }

        $View->setContent(
            new TableData( $tblInvoiceAll, null,
                array(
                    'Number' => 'Nummer',
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
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Zeigt alle noch nicht bestätigten Rechnungen an' );

        $tblInvoiceAllByIsConfirmedState = Billing::serviceInvoice()->entityInvoiceAllByIsConfirmedState(false);

        if (!empty( $tblInvoiceAllByIsConfirmedState )) {
            array_walk( $tblInvoiceAllByIsConfirmedState, function ( TblInvoice &$tblInvoice ) {
                $tblInvoice->Student = $tblInvoice->getServiceManagementPerson()->getFullName();
                $tblInvoice->Option =
                    ( new Primary( 'Bearbeiten und Freigeben', '/Sphere/Billing/Invoice/Edit',
                        new EditIcon(), array(
                            'Id' => $tblInvoice->getId()
                        ) ) )->__toString().
                    ( new Danger( 'Stornieren', '/Sphere/Billing/Invoice/Cancel',
                        new RemoveIcon(), array(
                            'Id' => $tblInvoice->getId()
                        ) ) )->__toString();
            } );
        }

        $View->setContent(
            new TableData( $tblInvoiceAllByIsConfirmedState, null,
                array(
                    'Number' => 'Nummer',
                    'Student' => 'Student',
                    'Option' => 'Option'
                )
            )
        );

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoiceEdit( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Rechnung' );
        $View->setDescription( 'Bearbeiten' );
        $View->setMessage( 'Hier können Sie die Rechnung bearbeiten' );
        $View->addButton( new Primary( 'Geprüft und Freigeben', '/Sphere/Billing/Invoice/Confirm',
            new OkIcon(), array(
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
                new TableData( $tblInvoiceItemAll, null,
                    array(
                        'CommodityName' => 'Leistung',
                        'ItemName'      => 'Artikel',
                        'ItemPrice'         => 'Preis',
                        'ItemQuantity'      => 'Menge',
                        'Option'        => 'Option'
                    )
                )
            );
        }

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoiceConfirm( $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Rechnung' );
        $View->setDescription( 'Bestätigen' );

        $tblInvoice = Billing::serviceInvoice()->entityInvoiceById( $Id );
        $View->setContent( Billing::serviceInvoice()->executeConfirmInvoice( $tblInvoice ) );

        return $View;
    }


    /**
     * @param $Id
     * @param $Route
     *
     * @return Stage
     */
    public static function frontendInvoiceCancel( $Id, $Route )
    {

        $View = new Stage();
        $View->setTitle( 'Rechnung' );
        $View->setDescription( 'Stornieren' );

        $tblInvoice = Billing::serviceInvoice()->entityInvoiceById( $Id );
        $View->setContent( Billing::serviceInvoice()->executeCancelInvoice( $tblInvoice, $Route ) );

        return $View;
    }
}
