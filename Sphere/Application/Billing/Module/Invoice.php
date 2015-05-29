<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Application\Billing\Frontend\Invoice as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
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

    }

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {
        self::$Configuration = $Configuration;

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice', __CLASS__.'::frontendInvoiceList'
        );
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
}
