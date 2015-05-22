<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Table\Type\Table;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;

/**
 * Class Invoicing
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
