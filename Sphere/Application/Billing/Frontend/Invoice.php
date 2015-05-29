<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
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
    public static function frontendInvoiceList()
    {

        $View = new Stage();
        $View->setTitle( 'Rechnungen' );
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Zeigt alle vorhandenen Rechnungen an' );

        $tblInvoiceAll = Billing::serviceInvoice()->entityInvoiceAll();

        if (!empty( $tblInvoiceAll )) {
            array_walk( $tblInvoiceAll, function ( TblInvoice &$tblInvoice ) {
                $tblInvoice->Option =
                    ( new Primary( 'Bearbeiten', '/Sphere/Billing/Invoice/Edit',
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
            new TableData( $tblInvoiceAll, null,
                array(
                    'Number' => 'Nummer',
                    'Option' => 'Option'
                )
            )
        );

        return $View;
    }
}
