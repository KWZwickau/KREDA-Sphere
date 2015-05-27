<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Application\Billing\Frontend\Invoice as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Commodity
 *
 * @package KREDA\Sphere\Application\Billing\Module
 */
class Invoice extends Common
{
    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Commodity/Select', 'Fakturierung anlegen', new PersonIcon()
        );
    }

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {
        self::$Configuration = $Configuration;

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Commodity/Select', __CLASS__.'::frontendBasketCommoditySelect'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Create', __CLASS__.'::frontendBasketCreate'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Item', __CLASS__.'::frontendBasketItemStatus'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Item/Remove', __CLASS__.'::frontendBasketItemRemove'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Item/Edit', __CLASS__.'::frontendBasketItemEdit'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'BasketItem', null );
    }

    /**
     * @return Stage
     */
    public static function frontendBasketCommoditySelect()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketCommoditySelect();
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
    public static function frontendBasketCreate( $Id)
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketCreate( $Id );
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
}
