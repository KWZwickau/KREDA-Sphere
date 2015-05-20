<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodityItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity;
use KREDA\Sphere\Application\Billing\Service\Commodity\EntityAction;
use KREDA\Sphere\Client\Frontend\Background\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;

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
     * @param int $Id
     * @return bool|TblItem
     */
    public function entityItemById($Id)
    {
        return parent::entityItemById($Id);
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
     * @param TblCommodity $tblCommodity
     * @param $Name
     * @param $Description
     *
     * @return TblCommodity
     */
    public function actionEditCommodity(
        TblCommodity $tblCommodity,
        $Name,
        $Description
    ) {
        return parent::actionEditCommodity(
            $tblCommodity,
            $Name,
            $Description
        );
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
        if (isset( $Commodity['Description'] ) && empty(  $Commodity['Description'] )) {
            $View->setError( 'Commodity[Description]', 'Bitte geben Sie eine Beschreibung an' );
            $Error = true;
        }

        if (!$Error) {
            $this->actionCreateCommodity(
                $Commodity['Name'],
                $Commodity['Description']
            );
            return new Success( 'Die Leistung wurde erfolgreich angelegt' )
            .new Redirect( '/Sphere/Billing/Commodity', 2);
        }
        return $View;
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
        if (isset( $Commodity['Description'] ) && empty(  $Commodity['Description'] )) {
            $View->setError( 'Commodity[Description]', 'Bitte geben Sie eine Beschreibung an' );
            $Error = true;
        }

        if (!$Error) {
            if ($this->actionEditCommodity(
                $tblCommodity, $Commodity['Name'], $Commodity['Description']
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
        if (isset( $Item['Description'] ) && empty(  $Item['Description'] )) {
            $View->setError( 'Item[Description]', 'Bitte geben Sie eine Beschreibung an' );
            $Error = true;
        }
        if (isset( $Item['Price'] ) && empty(  $Item['Price'] )) {
            $View->setError( 'Item[Price]', 'Bitte geben Sie einen Preis an' );
            $Error = true;
        }
        if (isset($Item['CostUnit'] ) && empty( $Item['CostUnit'] )) {
            $View->setError( 'Item[CostUnit]', 'Bitte geben Sie einen Namen an' );
            $Error = true;
        }

        if (!$Error) {
            $this->actionCreateItem(
                $Item['Name'],
                $Item['Description'],
                $Item['Price'],
                $Item['CostUnit']
            );
            return new Success( 'Der Artikel wurde erfolgreich angelegt' )
            .new Redirect( '/Sphere/Billing/Commodity', 2);
        }
        return $View;
    }
}
