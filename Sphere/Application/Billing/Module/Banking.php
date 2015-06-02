<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Application\Billing\Frontend\Banking as Frontend;

/**
 * Class Account
 *
 * @package KREDA\Sphere\Application\Billing\Module
 */
class Banking extends Common
{
    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

    }

    public static function registerApplication( Configuration $Configuration )
    {
        self::$Configuration = $Configuration;

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking', __CLASS__.'::frontendBanking'
        );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Person', __CLASS__.'::frontendBankingPerson'
        )   ->setParameterDefault( 'Id', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Person/Select', __CLASS__.'::frontendBankingPersonSelect'
        )   ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'Debtor', null );


    }

    /**
     * @return Stage
     */
    public static function frontendBanking()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBanking();
    }

    /**
     * @return Stage
     */
    public static function frontendBankingPerson()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingPerson();
    }

    /**
     * @param $Id
     * @return Stage
     */
    public static function frontendBankingPersonSelect( $Debtor, $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingPersonSelect( $Debtor, $Id );
    }

}