<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Invoice
 *
 * @package KREDA\Sphere\Application\Billing\Frontend
 */
class Invoicing extends AbstractFrontend
{
    /**
     * @return Stage
     */
    public static function frontendCommoditySelect()
    {
        $View = new Stage();
        $View->setTitle( 'Leistung' );
        $View->setDescription( 'Auswählen' );
        $View->setMessage( 'Bitte wählen Sie eine Leistung zur Fakturierung aus' );

        $tblCommodityAll = Billing::serviceCommodity()->entityCommodityAll();

        if (!empty($tblCommodityAll))
        {
            array_walk($tblCommodityAll, function (TblCommodity $tblCommodity)
            {
                $tblCommodity->DebtorCommodity = $tblCommodity->getTblDebtorCommodity()->getName();
                $tblCommodity->Type = $tblCommodity->getTblCommodityType()->getName();
                $tblCommodity->ItemCount = Billing::serviceCommodity()->countItemAllByCommodity( $tblCommodity );
                $tblCommodity->SumPriceItem = Billing::serviceCommodity()->sumPriceItemAllByCommodity( $tblCommodity)." €";
                $tblCommodity->Option =
                    (new Primary( 'Auswählen', '/Sphere/Billing/Commodity/Item/Select',
                        new EditIcon(), array(
                            'Id' => $tblCommodity->getId()
                        ) ))->__toString();
            });
        }

        $View->setContent(
            new TableData( $tblCommodityAll, null,
                array(
                    'Name'  => 'Name',
                    'Description' => 'Beschreibung',
                    'DebtorCommodity' => 'Debitor-Leistung',
                    'Type' => 'Leistungsart',
                    'ItemCount' => 'Artikelanzahl',
                    'SumPriceItem' => 'Gesamtpreis',
                    'Option'  => 'Option'
                )
            )
        );

        return $View;
    }
}
