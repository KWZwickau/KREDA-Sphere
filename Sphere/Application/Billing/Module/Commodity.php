<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Application\Billing\Frontend\Commodity as Frontend;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Commodity
 *
 * @package KREDA\Sphere\Application\Billing\Module
 */
class Commodity extends Common
{
    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Commodity/Item', 'Artikel', new PersonIcon()
        );
    }

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {
        self::$Configuration = $Configuration;

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity', __CLASS__.'::frontendStatus'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Create', __CLASS__.'::frontendCreate'
        )
            ->setParameterDefault( 'Commodity', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Delete', __CLASS__.'::frontendDelete'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Edit', __CLASS__.'::frontendEdit'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'Commodity', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Item', __CLASS__.'::frontendItemStatus'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Item/Create', __CLASS__.'::frontendItemCreate'
        )
            ->setParameterDefault( 'Item', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Item/Delete', __CLASS__.'::frontendItemDelete'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Item/Edit', __CLASS__.'::frontendItemEdit'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'Item', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Item/Select', __CLASS__.'::frontendItemSelect'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Item/Remove', __CLASS__.'::frontendItemRemove'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Item/Add', __CLASS__.'::frontendItemAdd'
        )
            ->setParameterDefault( 'tblCommodityId', null )
            ->setParameterDefault( 'tblItemId', null )
            ->setParameterDefault( 'Item', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Item/Account/Select', __CLASS__.'::frontendItemAccountSelect'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Item/Account/Add', __CLASS__.'::frontendItemAccountAdd'
        )
            ->setParameterDefault( 'tblAccountId', null )
            ->setParameterDefault( 'tblItemId', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Item/Account/Remove', __CLASS__.'::frontendItemAccountRemove'
        )
            ->setParameterDefault( 'Id', null );

    }

    /**
     * @return Stage
     */
    public static function frontendStatus()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendStatus();
    }

    /**
     * @param $Commodity
     *
     * @return Stage
     */
    public static function frontendCreate( $Commodity )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendCreate( $Commodity );
    }

    /**
     * @param $Id
     * @return Stage
     */
    public static function frontendDelete( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendDelete( $Id );
    }

    /**
     * @param $Id
     * @param $Commodity
     *
     * @return Stage
     */
    public static function frontendEdit( $Id, $Commodity )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendEdit( $Id, $Commodity );
    }

    /**
     * @return Stage
     */
    public static function frontendItemStatus()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendItemStatus();
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendItemSelect ( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendItemSelect( $Id);
    }

    /**
     * @param $tblCommodityId
     * @param $tblItemId
     * @param $Item
     * @return Stage
     */
    public static function frontendItemAdd ( $tblCommodityId, $tblItemId, $Item )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendItemAdd( $tblCommodityId, $tblItemId, $Item );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendItemRemove ( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendItemRemove( $Id);
    }

    /**
     * @param $Item
     *
     * @return Stage
     */
    public static function frontendItemCreate( $Item )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendItemCreate( $Item );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendItemDelete( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendItemDelete( $Id );
    }

    /**
     * @param $Id
     * @param $Item
     *
     * @return Stage
     */
    public static function frontendItemEdit( $Id, $Item )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendItemEdit( $Id, $Item );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendItemAccountSelect( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendItemAccountSelect( $Id );
    }

    /**
     * @param $tblItemId
     * @param $tblAccountId
     *
     * @return Stage
     */
    public static function frontendItemAccountAdd( $tblItemId, $tblAccountId )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendItemAccountAdd( $tblItemId, $tblAccountId );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendItemAccountRemove( $Id)
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendItemAccountRemove( $Id );
    }
}
