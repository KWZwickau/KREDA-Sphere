<?php
namespace KREDA\Sphere\Application\Billing;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BasketIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CommodityIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DocumentIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MoneyIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Billing
 *
 * @package KREDA\Sphere\Application\Billing
 */
class Billing extends Module\Commodity
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        /**
         * Configuration
         */
        self::setupApplicationAccess( 'Billing' );
        self::$Configuration = $Configuration;

        /**
         * Navigation
         */
        if (Gatekeeper::serviceAccess()->checkIsValidAccess( 'Application:Billing' )) {
            self::addClientNavigationMain( self::$Configuration, '/Sphere/Billing', 'Fakturierung', new MoneyIcon() );
            self::registerClientRoute( self::$Configuration, '/Sphere/Billing', __CLASS__.'::frontendBilling' );

            Module\Common::registerApplication( $Configuration );
            Module\Commodity::registerApplication( $Configuration );
            Module\Account::registerApplication( $Configuration );
            Module\Banking::registerApplication( $Configuration );
            Module\Basket::registerApplication( $Configuration );
            Module\Invoice::registerApplication( $Configuration );
            Module\Balance::registerApplication( $Configuration );
        }
    }

    /**
     * @return Stage
     */
    public static function frontendBilling()
    {

        self::setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Fraktuierung' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    /**
     * @return void
     */
    protected static function setupModuleNavigation()
    {

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Billing/Commodity', 'Leistungen', new CommodityIcon()
        );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Billing/Account', 'FIBU', new EditIcon()
        );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Billing/Banking', 'Debitor', new EditIcon()
        );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Billing/Basket', 'Fakturieren', new BasketIcon()
        );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Billing/Invoice', 'Rechnungen', new DocumentIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Billing/Balance', 'Offene Posten', new DocumentIcon()
        );
    }

    /**
     * @return Service\Account
     */
    public static function serviceAccount()
    {

        return Service\Account::getApi();
    }

    /**
     * @return Service\Banking
     */
    public static function serviceBanking()
    {

        return Service\Banking::getApi();
    }

    /**
     * @return Service\Commodity
     */
    public static function serviceCommodity()
    {

        return Service\Commodity::getApi();
    }

    /**
     * @return Service\Basket
     */
    public static function serviceBasket()
    {

        return Service\Basket::getApi();
    }

    /**
     * @return Service\Invoice
     */
    public static function serviceInvoice()
    {

        return Service\Invoice::getApi();
    }

    /**
     * @return Service\Balance
     */
    public static function serviceBalance()
    {

        return Service\Balance::getApi();
    }
}
