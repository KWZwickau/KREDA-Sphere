<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Application\Billing\Frontend\Basket as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Commodity
 *
 * @package KREDA\Sphere\Application\Billing\Module
 */
class Basket extends Common
{
    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

    }

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {
        self::$Configuration = $Configuration;

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Basket', __CLASS__.'::frontendBasketList'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Basket/Create', __CLASS__.'::frontendBasketCreate'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Basket/Delete', __CLASS__.'::frontendBasketDelete'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Basket/Commodity/Select', __CLASS__.'::frontendBasketCommoditySelect'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Basket/Commodity/Add', __CLASS__.'::frontendBasketCommodityAdd'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'CommodityId', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Basket/Commodity/Remove', __CLASS__.'::frontendBasketCommodityRemove'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'CommodityId', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Basket/Item', __CLASS__.'::frontendBasketItemStatus'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Basket/Item/Remove', __CLASS__.'::frontendBasketItemRemove'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Basket/Item/Edit', __CLASS__.'::frontendBasketItemEdit'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'BasketItem', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Basket/Person/Select', __CLASS__.'::frontendBasketPersonSelect'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Basket/Person/Add', __CLASS__.'::frontendBasketPersonAdd'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'PersonId', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Basket/Person/Remove', __CLASS__.'::frontendBasketPersonRemove'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'PersonId', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Basket/Summary', __CLASS__.'::frontendBasketSummary'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'Basket', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Basket/Debtor/Select', __CLASS__.'::frontendBasketDebtorSelect'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'Debtor', null );
    }

    /**
     * @return Stage
     */
    public static function frontendBasketList()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketList();
    }

    /**
     * @return Stage
     */
    public static function frontendBasketCreate()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketCreate();
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketDelete( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketDelete( $Id );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketCommoditySelect( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketCommoditySelect( $Id );
    }

    /**
     * @param $Id
     * @param $CommodityId
     *
     * @return Stage
     */
    public static function frontendBasketCommodityAdd( $Id, $CommodityId )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketCommodityAdd( $Id, $CommodityId );
    }

    /**
     * @param $Id
     * @param $CommodityId
     *
     * @return Stage
     */
    public static function frontendBasketCommodityRemove( $Id, $CommodityId )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketCommodityRemove( $Id, $CommodityId );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketItemStatus( $Id)
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketItemStatus( $Id );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketItemRemove( $Id)
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketItemRemove( $Id );
    }

    /**
     * @param $Id
     * @param $BasketItem
     *
     * @return Stage
     */
    public static function frontendBasketItemEdit( $Id, $BasketItem )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketItemEdit( $Id, $BasketItem );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketPersonSelect( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketPersonSelect( $Id );
    }

    /**
     * @param $Id
     * @param $PersonId
     *
     * @return Stage
     */
    public static function frontendBasketPersonAdd( $Id, $PersonId )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketPersonAdd( $Id, $PersonId );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketPersonRemove( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketPersonRemove( $Id );
    }

    /**
     * @param $Id
     * @param $Basket
     *
     * @return Stage
     */
    public static function frontendBasketSummary( $Id, $Basket )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketSummary( $Id, $Basket );
    }

    /**
     * @param $Id
     * @param $Debtor
     *
     * @return Stage
     */
    public static function frontendBasketDebtorSelect( $Id, $Debtor )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketDebtorSelect( $Id, $Debtor );
    }
}
