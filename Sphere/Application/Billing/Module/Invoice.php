<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Application\Billing\Frontend\Invoice as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Invoice
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
            '/Sphere/Billing/Invoice/List', 'Alle'
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Invoice/IsNotConfirmed', 'Offene'
        );
    }

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {
        self::$Configuration = $Configuration;

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice', __CLASS__.'::frontendInvoiceStatus'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/List', __CLASS__.'::frontendInvoiceList'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/IsNotConfirmed', __CLASS__.'::frontendInvoiceIsNotConfirmedList'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Edit', __CLASS__.'::frontendInvoiceEdit'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Confirm', __CLASS__.'::frontendInvoiceConfirm'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Cancel', __CLASS__.'::frontendInvoiceCancel'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Item/Edit', __CLASS__.'::frontendInvoiceItemEdit'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'InvoiceItem', null);
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Item/Remove', __CLASS__.'::frontendInvoiceItemRemove'
        )
            ->setParameterDefault( 'Id', null );
    }

    /**
     * @return Stage
     */
    public static function frontendInvoiceStatus()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceStatus();
    }

    /**
     * @return Stage
     */
    public static function frontendInvoiceList()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceList();
    }

    /**
     * @return Stage
     */
    public static function frontendInvoiceIsNotConfirmedList()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceIsNotConfirmedList();
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoiceEdit( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceEdit( $Id );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoiceConfirm( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceConfirm( $Id );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoiceCancel( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceCancel( $Id );
    }

    /**
     * @param $Id
     * @param $InvoiceItem
     *
     * @return Stage
     */
    public static function frontendInvoiceItemEdit( $Id, $InvoiceItem )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceItemEdit( $Id, $InvoiceItem );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoiceItemRemove( $Id)
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceItemRemove( $Id );
    }
}
