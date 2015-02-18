<?php
namespace KREDA\Sphere\Application\Billing;

use KREDA\Sphere\Application\Billing\Frontend\Summary\Summary;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MoneyIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Billing
 *
 * @package KREDA\Sphere\Application\Billing
 */
class Billing extends AbstractApplication
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     *
     * @return Configuration
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
        self::addClientNavigationMain( self::$Configuration, '/Sphere/Billing', 'Fakturierung', new MoneyIcon() );
        /**
         *
         */
        self::registerClientRoute( self::$Configuration, '/Sphere/Billing', __CLASS__.'::frontendBilling' );

        return $Configuration;
    }

    /**
     * @return Stage
     */
    public function frontendBilling()
    {

        $this->setupModuleNavigation();
        return Summary::stageSummary();
    }

    /**
     * @return void
     */
    protected function setupModuleNavigation()
    {

        self::addModuleNavigationMain( self::$Configuration, '/Sphere/Billing', 'Fakturierung', new MoneyIcon() );
    }
}
