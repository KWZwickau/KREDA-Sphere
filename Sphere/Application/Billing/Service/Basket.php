<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasketItem;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasketPerson;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Billing\Service\Basket\EntityAction;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Commodity
 *
 * @package KREDA\Sphere\Application\Billing\Service
 */
class Basket extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Billing', 'Basket', $this->getConsumerSuffix() );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

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
     * @return bool|\KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket[]
     */
    public function entityBasketAll()
    {
        return parent::entityBasketAll();
    }

    /**
     * @param TblBasket $tblBasket
     *
     * @return bool|TblCommodity[]
     */
    public function entityCommodityAllByBasket(TblBasket $tblBasket)
    {
        return parent::entityCommodityAllByBasket($tblBasket);
    }

    /**
     * @param $Id
     *
     * @return bool|\KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasketItem
     */
    public function entityBasketItemById($Id)
    {
        return parent::entityBasketItemById($Id);
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
     * @param $Id
     *
     * @return bool|TblBasketPerson
     */
    public  function entityBasketPersonById($Id)
    {
        return parent::entityBasketPersonById($Id);
    }

    /**
     * @param TblBasket $tblBasket
     *
     * @return bool|TblBasketPerson[]
     */
    public function entityBasketPersonAllByBasket(TblBasket $tblBasket)
    {
        return parent::entityBasketPersonAllByBasket($tblBasket);
    }

    /**
     * @param TblBasket $tblBasket
     *
     * @return null|TblPerson[]
     */
    public function entityPersonAllByBasket(TblBasket $tblBasket)
    {
        $tblBasketPersonList = $this->entityBasketPersonAllByBasket($tblBasket);
        $tblPerson = array();
        foreach($tblBasketPersonList as $tblBasketPerson)
        {
            array_push($tblPerson, $tblBasketPerson->getServiceManagementPerson());
        }

        return $tblPerson;
    }

    /**
     * @return string
     */
    public function executeCreateBasket(
    )
    {
        $tblBasket = $this->actionCreateBasket();
        if ($tblBasket)
        {
            return new Success( 'Der Warenkorb wurde erfolgreich erstellt' )
                .new Redirect( '/Sphere/Billing/Basket/Commodity/Select', 1, array( 'Id' => $tblBasket->getId()) );
        }
        else
        {
            return new Warning( 'Der Warenkorb konnte nicht erstellt werden' )
                .new Redirect( '/Sphere/Billing/Basket', 1 );
        }
    }

    /**
     * @param TblBasket $tblBasket
     *
     * @return string
     */
    public function executeDestroyBasket(
        TblBasket $tblBasket
    )
    {
        $tblBasket = $this->actionDestroyBasket($tblBasket);
        if ($tblBasket)
        {
            return new Success( 'Der Warenkorb wurde erfolgreich gelöscht' )
                .new Redirect( '/Sphere/Billing/Basket', 1 );
        }
        else
        {
            return new Warning( 'Der Warenkorb konnte nicht gelöscht werden' )
                .new Redirect( '/Sphere/Billing/Basket', 1 );
        }
    }

    /**
     * @param TblBasket $tblBasket
     * @param TblCommodity $tblCommodity
     *
     * @return string
     */
    public function executeAddBasketCommodity(
        TblBasket $tblBasket,
        TblCommodity $tblCommodity
    )
    {
        if ($this->actionCreateBasketItemsByCommodity($tblBasket, $tblCommodity))
        {
            return new Success( 'Die Leistung ' . $tblCommodity->getName() . ' wurde erfolgreich hinzugefügt' )
                .new Redirect( '/Sphere/Billing/Basket/Commodity/Select', 0, array( 'Id' => $tblBasket->getId()) );
        }
        else
        {
            return new Warning( 'Die Leistung ' . $tblCommodity->getName() . ' konnte nicht hinzugefügt werden' )
                .new Redirect( '/Sphere/Billing/Basket/Commodity/Select', 2, array( 'Id' => $tblBasket->getId()) );
        }
    }

    /**
     * @param TblBasket $tblBasket
     * @param TblCommodity $tblCommodity
     *
     * @return string
     */
    public function executeRemoveBasketCommodity(
        TblBasket $tblBasket,
        TblCommodity $tblCommodity
    )
    {
        if ($this->actionDestroyBasketItemsByCommodity($tblBasket, $tblCommodity))
        {
            return new Success( 'Die Leistung ' . $tblCommodity->getName() . ' wurde erfolgreich entfernt' )
            .new Redirect( '/Sphere/Billing/Basket/Commodity/Select', 0, array( 'Id' => $tblBasket->getId()) );
        }
        else
        {
            return new Warning( 'Die Leistung ' . $tblCommodity->getName() . ' konnte nicht entfernt werden' )
            .new Redirect( '/Sphere/Billing/Basket/Commodity/Select', 2, array( 'Id' => $tblBasket->getId()) );
        }
    }

    /**
     * @param TblBasketItem $tblBasketItem
     *
     * @return string
     */
    public function executeRemoveBasketItem(
        TblBasketItem $tblBasketItem
    )
    {
        if ($this->actionRemoveBasketItem($tblBasketItem))
        {
            return new Success( 'Der Artikel ' . $tblBasketItem->getServiceBillingCommodityItem()->getTblItem()->getName() . ' wurde erfolgreich entfernt' )
                .new Redirect( '/Sphere/Billing/Basket/Item', 0, array( 'Id' => $tblBasketItem->getTblBasket()->getId()) );
        }
        else
        {
            return new Warning( 'Der Artikel ' . $tblBasketItem->getServiceBillingCommodityItem()->getTblItem()->getName() . ' konnte nicht entfernt werden' )
                .new Redirect( '/Sphere/Billing/Basket/Item', 2, array( 'Id' => $tblBasketItem->getTblBasket()->getId()) );
        }
    }

    /**
     * @param AbstractType $View
     * @param TblBasketItem $tblBasketItem
     * @param $BasketItem
     *
     * @return AbstractType|string
     */
    public function executeEditBasketItem(
        AbstractType &$View = null,
        TblBasketItem $tblBasketItem,
        $BasketItem
    ) {

        /**
         * Skip to Frontend
         */
        if (null === $BasketItem
        ) {
            return $View;
        }

        $Error = false;

        if (isset( $BasketItem['Price'] ) && empty(  $BasketItem['Price'] )) {
            $View->setError( 'BasketItem[Price]', 'Bitte geben Sie einen Preis an' );
            $Error = true;
        }
        if (isset( $BasketItem['Quantity'] ) && empty(  $BasketItem['Quantity'] )) {
            $View->setError( 'BasketItem[Quantity]', 'Bitte geben Sie eine Menge an' );
            $Error = true;
        }

        if (!$Error) {
            if ($this->actionEditBasketItem(
                $tblBasketItem,
                $BasketItem['Price'],
                $BasketItem['Quantity']
            )) {
                $View .= new Success( 'Änderungen gespeichert, die Daten werden neu geladen...' )
                    .new Redirect( '/Sphere/Billing/Basket/Item', 1, array( 'Id' => $tblBasketItem->getTblBasket()->getId()) );
            } else {
                $View .= new Danger( 'Änderungen konnten nicht gespeichert werden' )
                    .new Redirect( '/Sphere/Billing/Basket/Item', 2, array( 'Id' => $tblBasketItem->getTblBasket()->getId()) );
            };
        }
        return $View;
    }

    /**
     * @param TblBasket $tblBasket
     * @param TblPerson $tblPerson
     *
     * @return string
     */
    public function executeAddBasketPerson(
        TblBasket $tblBasket,
        TblPerson $tblPerson
    )
    {
        if ($this->actionAddBasketPerson($tblBasket, $tblPerson))
        {
            return new Success( 'Die Person ' . $tblPerson->getFullName() . ' wurde erfolgreich hinzugefügt' )
                .new Redirect( '/Sphere/Billing/Basket/Person/Select', 0, array( 'Id' => $tblBasket->getId()) );
        }
        else
        {
            return new Warning( 'Die Person ' . $tblPerson->getFullName() . ' konnte nicht hinzugefügt werden' )
                .new Redirect( '/Sphere/Billing/Basket/Person/Select', 2, array( 'Id' => $tblBasket->getId()) );
        }
    }

    /**
     * @param \KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasketPerson $tblBasketPerson
     *
     * @return string
     */
    public function executeRemoveBasketPerson(
        TblBasketPerson $tblBasketPerson
    )
    {
        if ($this->actionRemoveBasketPerson($tblBasketPerson))
        {
            return new Success( 'Die Person ' . $tblBasketPerson->getServiceManagementPerson()->getFullName() . ' wurde erfolgreich entfernt' )
                .new Redirect( '/Sphere/Billing/Basket/Person/Select', 0, array( 'Id' => $tblBasketPerson->getTblBasket()->getId()) );
        }
        else
        {
            return new Warning( 'Die Person ' .$tblBasketPerson->getServiceManagementPerson()->getFullName() .  ' konnte nicht entfernt werden' )
                .new Redirect( '/Sphere/Billing/Basket/Person/Select', 2, array( 'Id' => $tblBasketPerson->getTblBasket()->getId()) );
        }
    }

    /**
     * @param AbstractType $View
     * @param TblBasket $tblBasket
     * @param $Basket
     *
     * @return AbstractType
     */
    public function executeCreateInvoiceFromBasketList(
        AbstractType &$View = null,
        TblBasket $tblBasket,
        $Basket
    )
    {
        /**
         * Skip to Frontend
         */
        if (null === $Basket
        ) {
            return $View;
        }
        // TODO actionCreateInvoices
        $this->actionDestroyBasket( $tblBasket );
        $View.= new Success( 'Die Rechnungen wurden erfolgreich erstellt' )
                    .new Redirect( '/Sphere/Billing/Basket', 2 );

        return $View;
    }
}
