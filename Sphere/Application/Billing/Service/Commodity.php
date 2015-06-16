<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodityItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodityType;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblItemAccount;
use KREDA\Sphere\Application\Billing\Service\Commodity\EntityAction;
use KREDA\Sphere\Application\Billing\Service\Commodity\TblDebtorCommodity;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Frontend\Background\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Client\Frontend\Form\AbstractType as AbstractType;

/**
 * Class Commodity
 *
 * @package KREDA\Sphere\Application\Billing\Service
 */
class Commodity extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {
        $this->setDatabaseHandler( 'Billing', 'Commodity', $this->getConsumerSuffix() );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {
        /**
         * CommodityType
         */
        $this->actionCreateCommodityType( 'Einzelleistung' );
        $this->actionCreateCommodityType( 'Sammelleistung' );
    }

    /**
     * @param int $Id
     *
     * @return bool|TblCommodity
     */
    public function entityCommodityById( $Id )
    {
        return parent::entityCommodityById( $Id );
    }

    /**
     * @return bool|TblCommodity[]
     */
    public function entityCommodityAll()
    {
        return parent::entityCommodityAll();
    }

    /**
     * @param $Id
     *
     * @return bool|TblCommodityType
     */
    public function entityCommodityTypeById($Id)
    {
        return parent::entityCommodityTypeById($Id);
    }

    /**
     * @return bool|TblCommodityType[]
     */
    public function entityCommodityTypeAll()
    {
        return parent::entityCommodityTypeAll();
    }

    /**
     * @param int $Id
     * @return bool|TblItem
     */
    public function entityItemById($Id)
    {
        return parent::entityItemById($Id);
    }

    /**
     * @param string $Name
     * @return bool|TblItem
     */
    public function entityItemByName($Name)
    {
        return parent::entityItemByName($Name);
    }

    /**
     * @return bool|TblItem[]
     */
    public function entityItemAll()
    {
        return parent::entityItemAll();
    }

    /**
     * @param TblCommodity $tblCommodity
     *
     * @return bool|Commodity\Entity\TblItem[]
     */
    public function entityItemAllByCommodity( TblCommodity $tblCommodity)
    {
        return parent::entityItemAllByCommodity($tblCommodity);
    }

    /**
     * @param TblCommodity $tblCommodity
     *
     * @return int
     */
    public function countItemAllByCommodity ( TblCommodity $tblCommodity )
    {
        return parent::countItemAllByCommodity( $tblCommodity );
    }

    /**
     * @param TblCommodity $tblCommodity
     *
     * @return bool|Entity\TblItem[]
     */
    public function entityCommodityItemAllByCommodity(TblCommodity $tblCommodity)
    {
        return parent::entityCommodityItemAllByCommodity($tblCommodity);
    }

    /**
     * @param TblItem $tblItem
     *
     * @return bool|TblItem[]
     */
    public  function entityCommodityItemAllByItem(TblItem $tblItem)
    {
        return parent::entityCommodityItemAllByItem($tblItem);
    }

    /**
     * @param $Id
     *
     * @return bool|TblCommodityItem
     */
    public  function entityCommodityItemById($Id)
    {
        return parent::entityCommodityItemById($Id);
    }

    /**
     * @param $Id
     *
     * @return bool|TblItemAccount
     */
    public function entityItemAccountById($Id)
    {
        return parent::entityItemAccountById($Id);
    }


    /**
     * @param TblItem $tblItem
     *
     * @return bool|TblItemAccount
     */
    public function entityItemAccountAllByItem(TblItem $tblItem)
    {
        return parent::entityItemAccountAllByItem($tblItem);
    }

    /**
     * @param TblItem $tblItem
     *
     * @return Account\Entity\TblAccount[]
     */
    public function entityAccountAllByItem(TblItem $tblItem)
    {
        return parent::entityAccountAllByItem($tblItem);
    }


    /**
     * @param TblCommodity $tblCommodity
     *
     * @return string
     */
    public function sumPriceItemAllByCommodity(TblCommodity $tblCommodity)
    {
        return parent::sumPriceItemAllByCommodity($tblCommodity);
    }

    /**
     * @param \KREDA\Sphere\Client\Frontend\Form\AbstractType $View
     *
     * @param $Commodity
     *
     * @return \KREDA\Sphere\Client\Frontend\Form\AbstractType
     */
    public function executeCreateCommodity(
        AbstractType &$View = null,
        $Commodity
    ) {

        /**
         * Skip to Frontend
         */
        if (null === $Commodity
        ) {
            return $View;
        }

        $Error = false;

        if (isset($Commodity['Name'] ) && empty( $Commodity['Name'] )) {
            $View->setError( 'Commodity[Name]', 'Bitte geben Sie einen Namen an' );
            $Error = true;
        }
//        if (isset( $Commodity['Description'] ) && empty(  $Commodity['Description'] )) {
//            $View->setError( 'Commodity[Description]', 'Bitte geben Sie eine Beschreibung an' );
//            $Error = true;
//        }

        if (!$Error)
        {
            $this->actionCreateCommodity(
                $Commodity['Name'],
                $Commodity['Description'],
                $this->entityCommodityTypeById($Commodity['Type'])
            );
            return new Success( 'Die Leistung wurde erfolgreich angelegt' )
            .new Redirect( '/Sphere/Billing/Commodity', 2);
        }
        return $View;
    }


    /**
     * @param TblCommodity $tblCommodity
     * @return string
     */
    public function executeRemoveCommodity(
        TblCommodity $tblCommodity
    )
    {
        if (null === $tblCommodity)
        {
            return '';
        }

        if ($this->actionRemoveCommodity($tblCommodity))
        {
            return new Success( 'Die Leistung wurde erfolgreich gelöscht')
                .new Redirect( '/Sphere/Billing/Commodity', 2);
        }
        else
        {
            return new Danger( 'Die Leistung konnte nicht gelöscht werden' )
                .new Redirect( '/Sphere/Billing/Commodity', 2);
        }
    }

    /**
     * @param AbstractType $View
     * @param TblCommodity $tblCommodity
     * @param $Commodity
     *
     * @return AbstractType|string
     */
    public function executeEditCommodity(
        AbstractType &$View = null,
        TblCommodity $tblCommodity,
        $Commodity
    ) {

        /**
         * Skip to Frontend
         */
        if (null === $Commodity
        ) {
            return $View;
        }

        $Error = false;

        if (isset($Commodity['Name'] ) && empty( $Commodity['Name'] )) {
            $View->setError( 'Commodity[Name]', 'Bitte geben Sie einen Namen an' );
            $Error = true;
        }
//        if (isset( $Commodity['Description'] ) && empty(  $Commodity['Description'] )) {
//            $View->setError( 'Commodity[Description]', 'Bitte geben Sie eine Beschreibung an' );
//            $Error = true;
//        }

        if (!$Error) {
            if ($this->actionEditCommodity(
                $tblCommodity,
                $Commodity['Name'],
                $Commodity['Description'],
                $this->entityCommodityTypeById( $Commodity['Type'] )
            )) {
                $View .= new Success( 'Änderungen gespeichert, die Daten werden neu geladen...' )
                    .new Redirect( '/Sphere/Billing/Commodity', 2);
            } else {
                $View .= new Danger( 'Änderungen konnten nicht gespeichert werden' );
            };
        }
        return $View;
    }

    /**
     * @param AbstractType $View
     * @param $Item
     *
     * @return AbstractType|string
     */
    public function executeCreateItem(
        AbstractType &$View = null,
        $Item
    ) {

        /**
         * Skip to Frontend
         */
        if (null === $Item
        ) {
            return $View;
        }

        $Error = false;

        if (isset($Item['Name'] ) && empty( $Item['Name'] )) {
            $View->setError( 'Item[Name]', 'Bitte geben Sie einen Namen an' );
            $Error = true;
        }
//        if (isset( $Item['Description'] ) && empty(  $Item['Description'] )) {
//            $View->setError( 'Item[Description]', 'Bitte geben Sie eine Beschreibung an' );
//            $Error = true;
//        }
        if (isset( $Item['Price'] ) && empty(  $Item['Price'] )) {
            $View->setError( 'Item[Price]', 'Bitte geben Sie einen Preis an' );
            $Error = true;
        }
//        if (isset($Item['CostUnit'] ) && empty( $Item['CostUnit'] )) {
//            $View->setError( 'Item[CostUnit]', 'Bitte geben Sie einen Namen an' );
//            $Error = true;
//        }

        if (!$Error) {
            $this->actionCreateItem(
                $Item['Name'],
                $Item['Description'],
                $Item['Price'],
                $Item['CostUnit'],
                $Item['Course'],
                $Item['ChildRank']
            );
            return new Success( 'Der Artikel wurde erfolgreich angelegt' )
            .new Redirect( '/Sphere/Billing/Commodity/Item', 2);
        }
        return $View;
    }

    /**
     * @param TblItem $tblItem
     *
     * @return string
     */
    public function executeDeleteItem(
        TblItem $tblItem
    )
    {
        if (null === $tblItem)
        {
            return '';
        }

        if ($this->actionDestroyItem( $tblItem ))
        {
            return new Success( 'Der Artikel wurde erfolgreich gelöscht')
            .new Redirect( '/Sphere/Billing/Commodity/Item', 2);
        }
        else
        {
            return new Danger( 'Der Artikel konnte nicht gelöscht werden. Überprüfen Sie ob er noch in einer Leistung verwendet wird.' )
            .new Redirect( '/Sphere/Billing/Commodity/Item', 2);
        }
    }

    /**
     * @param AbstractType $View
     * @param TblItem $tblItem
     * @param $Item
     *
     * @return AbstractType|string
     */
    public function executeEditItem(
        AbstractType &$View = null,
        TblItem $tblItem,
        $Item
    ) {

        /**
         * Skip to Frontend
         */
        if (null === $Item
        ) {
            return $View;
        }

        $Error = false;

        if (isset($Item['Name'] ) && empty( $Item['Name'] )) {
            $View->setError( 'Item[Name]', 'Bitte geben Sie einen Namen an' );
            $Error = true;
        }
//        if (isset( $Item['Description'] ) && empty(  $Item['Description'] )) {
//            $View->setError( 'Item[Description]', 'Bitte geben Sie eine Beschreibung an' );
//            $Error = true;
//        }
        if (isset( $Item['Price'] ) && empty(  $Item['Price'] )) {
            $View->setError( 'Item[Price]', 'Bitte geben Sie einen Preis an' );
            $Error = true;
        }
//        if (isset( $Item['CostUnit'] ) && empty(  $Item['CostUnit'] )) {
//            $View->setError( 'Item[CostUnit]', 'Bitte geben Sie eine Kostenstelle an' );
//            $Error = true;
//        }

        if (!$Error) {
            if ($this->actionEditItem(
                $tblItem,
                $Item['Name'],
                $Item['Description'],
                $Item['Price'],
                $Item['CostUnit'],
                $Item['Course'],
                $Item['ChildRank']
            )) {
                $View .= new Success( 'Änderungen gespeichert, die Daten werden neu geladen...' )
                    .new Redirect( '/Sphere/Billing/Commodity/Item', 2);
            } else {
                $View .= new Danger( 'Änderungen konnten nicht gespeichert werden' );
            };
        }
        return $View;
    }

    /**
     * @param TblCommodityItem $tblCommodityItem
     * @return string
     */
    public function executeRemoveCommodityItem(
        TblCommodityItem $tblCommodityItem
    )
    {
        if ($this->actionRemoveCommodityItem($tblCommodityItem))
        {
            return new Success( 'Der Artikel ' . $tblCommodityItem->getTblItem()->getName(). ' wurde erfolgreich entfernt' )
                .new Redirect( '/Sphere/Billing/Commodity/Item/Select', 2, array( 'Id' => $tblCommodityItem->getTblCommodity()->getId()) );
        }
        else
        {
            return new Warning( 'Der Artikel ' . $tblCommodityItem->getTblItem()->getName(). ' konnte nicht entfernt werden' )
                .new Redirect( '/Sphere/Billing/Commodity/Item/Select', 2, array( 'Id' => $tblCommodityItem->getTblCommodity()->getId()) );
        }
    }

    /**
     * @param TblCommodity $tblCommodity
     * @param TblItem $tblItem
     * @param $Item
     * @return string
     */
    public function executeAddCommodityItem(
        TblCommodity $tblCommodity,
        TblItem $tblItem,
        $Item
    )
    {
        if ($this->actionAddCommodityItem($tblCommodity, $tblItem, $Item['Quantity']))
        {
            return new Success( 'Der Artikel ' . $tblItem->getName(). ' wurde erfolgreich hinzugefügt' )
                .new Redirect( '/Sphere/Billing/Commodity/Item/Select', 2, array( 'Id' => $tblCommodity->getId()) );
        }
        else
        {
            return new Warning( 'Der Artikel ' .$tblItem->getName(). ' konnte nicht entfernt werden' )
                .new Redirect( '/Sphere/Billing/Commodity/Item/Select', 2, array( 'Id' => $tblCommodity->getId()) );
        }
    }

    /**
     * @param TblItem $tblItem
     * @param TblAccount $tblAccount
     *
     * @return string
     */
    public function executeAddItemAccount(
        TblItem $tblItem,
        TblAccount $tblAccount
    )
    {
        if ($this->actionAddItemAccount($tblItem, $tblAccount))
        {
            return new Success( 'Das FIBU-Konto ' . $tblAccount->getDescription(). ' wurde erfolgreich hinzugefügt' )
            .new Redirect( '/Sphere/Billing/Commodity/Item/Account/Select', 0, array( 'Id' => $tblItem->getId()) );
        }
        else
        {
            return new Warning( 'Das FIBU-Konto ' .$tblAccount->getDescription(). ' konnte nicht entfernt werden' )
            .new Redirect( '/Sphere/Billing/Commodity/Item/Account/Select', 2, array( 'Id' => $tblItem->getId()) );
        }
    }

    /**
     * @param TblItemAccount $tblItemAccount
     *
     * @return string
     */
    public function executeRemoveItemAccount(
        TblItemAccount $tblItemAccount
    )
    {
        if ($this->actionRemoveItemAccount($tblItemAccount))
        {
            return new Success( 'Das FIBU-Konto ' . $tblItemAccount->getServiceBilling_Account()->getDescription(). ' wurde erfolgreich entfernt' )
            .new Redirect( '/Sphere/Billing/Commodity/Item/Account/Select', 0, array( 'Id' => $tblItemAccount->getTblItem()->getId()) );
        }
        else
        {
            return new Warning( 'Das FIBU-Konto ' . $tblItemAccount->getServiceBilling_Account()->getDescription(). ' konnte nicht entfernt werden' )
            .new Redirect( '/Sphere/Billing/Commodity/Item/Account/Select', 2, array( 'Id' => $tblItemAccount->getTblItem()->getId()) );
        }
    }
}
