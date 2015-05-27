<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblBasketItem;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\EntityAction;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Commodity
 *
 * @package KREDA\Sphere\Application\Billing\Service
 */
class Invoice extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Billing', 'Invoice', $this->getConsumerSuffix() );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

    }

    /**
     * @param int $Id
     *
     * @return bool|TblInvoice
     */
    public function entityInvoiceById($Id)
    {
        return parent::entityInvoiceById($Id);
    }

    /**
     * @param $Id
     *
     * @return bool|TblBasket
     */
    public  function entityBasketById($Id)
    {
        return parent::entityBasketById($Id);
    }

    /**
     * @param TblBasket $tblBasket
     *
     * @return bool|TblBasketItem[]
     */
    public function entityBasketItemAllByBasket(TblBasket $tblBasket)
    {
        return parent::entityBasketItemAllByBasket($tblBasket);
    }

    /**
     * @param TblCommodity $tblCommodity
     *
     * @return string
     */
    public function executeCreateBasket(
        TblCommodity $tblCommodity
    )
    {
        $tblBasket = $this->actionCreateBasketItemsByCommodity($tblCommodity);
        if ($tblBasket)
        {
            return new Success( 'Der Warenkorb wurde erfolgreich erstellt' )
            .new Redirect( '/Sphere/Billing/Invoice/Basket/Item', 1, array( 'Id' => $tblBasket->getId()) );
        }
        else
        {
            return new Warning( 'Der Warenkorb konnte nicht erstellt werden' )
            .new Redirect( '/Sphere/Billing/Invoice/Basket/Commodity/Select', 1 );
        }
    }
}
