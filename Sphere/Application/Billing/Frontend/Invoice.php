<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblBasketItem;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Student\Entity\TblStudent;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
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
    public static function frontendBasketCommoditySelect()
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
                $tblCommodity->Type = $tblCommodity->getTblCommodityType()->getName();
                $tblCommodity->ItemCount = Billing::serviceCommodity()->countItemAllByCommodity( $tblCommodity );
                $tblCommodity->SumPriceItem = Billing::serviceCommodity()->sumPriceItemAllByCommodity( $tblCommodity)." €";
                $tblCommodity->Option =
                    (new Primary( 'Auswählen', '/Sphere/Billing/Invoice/Basket/Create',
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
                    'Type' => 'Leistungsart',
                    'ItemCount' => 'Artikelanzahl',
                    'SumPriceItem' => 'Gesamtpreis',
                    'Option'  => 'Option'
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
    public static function frontendBasketCreate( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Warenkorb' );
        $View->setDescription( 'Erstellen' );

        $tblCommodity = Billing::serviceCommodity()->entityCommodityById($Id);
        $View->setContent(Billing::serviceInvoice()->executeCreateBasket( $tblCommodity ));

        return $View;
    }


    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketItemStatus( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Warenkorb Artikel' );
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Zeigt die Artikel im Warenkorb' );

        $tblBasket = Billing::serviceInvoice()->entityBasketById( $Id );
        $tblBasketItemAll = Billing::serviceInvoice()->entityBasketItemAllByBasket( $tblBasket );

        if (!empty($tblBasketItemAll))
        {
            array_walk($tblBasketItemAll, function (TblBasketItem &$tblBasketItem)
            {
                $tblCommodity = $tblBasketItem->getServiceBillingCommodityItem()->getTblCommodity();
                $tblItem = $tblBasketItem->getServiceBillingCommodityItem()->getTblItem();
                $tblBasketItem->CommodityName = $tblCommodity->getName();
                $tblBasketItem->ItemName = $tblItem->getName();
                $tblBasketItem->Option =
                        (new Primary( 'Bearbeiten', '/Sphere/Billing/Basket/Item/Edit',
                            new EditIcon(), array(
                                'Id' => $tblBasketItem->getId()
                            ) ) )->__toString().
                        (new \KREDA\Sphere\Client\Frontend\Button\Link\Danger( 'Entfernen', '/Sphere/Billing/Basket/Item/Remove',
                            new RemoveIcon(), array(
                                'Id' => $tblBasketItem->getId()
                            ) ) )->__toString();
            });
        }

        $View->setContent(
            new TableData( $tblBasketItemAll, null,
                array(
                    'CommodityName'  => 'Leistung',
                    'ItemName' => 'Artikel',
                    'Price' => 'Preis',
                    'Quantity' => 'Menge',
                    'Option'  => 'Option'
                )
            )
        );

        return $View;
    }

    /**
     * @return Stage
     */
    public static function frontendStudentSelect()
    {
        $View = new Stage();
        $View->setTitle( 'Schüler' );
        $View->setDescription( 'Auswählen' );
        $View->setMessage( 'Bitte wählen Sie Schüler zur Fakturierung aus' );

        $tblStudentAll = Management::servicePerson()->entityPersonAllByType( Management::servicePerson()->entityPersonTypeByName('Schüler'));

        if (!empty($tblStudentAll))
        {
            array_walk($tblStudentAll, function (TblPerson &$tblPerson)
            {
                $tblPerson->Option =
                    (new Primary( 'Auswählen', '/Sphere/Billing/Invoice/Person/Select',
                        new EditIcon(), array(
                            'Id' => $tblPerson->getId()
                        ) ))->__toString();
            });
        }

        $View->setContent(
            new TableData( $tblStudentAll, null,
                array(
                    'FirstName'  => 'Vorname',
                    'LastName' => 'Nachname',
                    'Option'  => 'Option'
                )
            )
        );

        return $View;
    }
}
