<?php
namespace KREDA\Sphere\Application\Billing\Module;

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

    /**
     * @param Configuration $Configuration
     */
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

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Delete', __CLASS__.'::frontendBankingDelete'
        )   ->setParameterDefault( 'Id', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Select/Commodity', __CLASS__.'::frontendBankingSelectCommodity'
        )   ->setParameterDefault( 'Id', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Commodity/Remove', __CLASS__.'::frontendBankingRemoveCommodity'
        )   ->setParameterDefault( 'Id', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Commodity/Add', __CLASS__.'::frontendBankingAddCommodity'
        )   ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'CommodityId', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Select/Reference', __CLASS__.'::frontendBankingSelectReference'
        )   ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'Reference', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Select/Reference/Delete', __CLASS__.'::frontendBankingSelectReferenceDelete'
        )   ->setParameterDefault( 'Id', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Select/Reference/Deactivate', __CLASS__.'::frontendBankingReferenceDeactivate'
        )   ->setParameterDefault( 'Id', null );
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
     * @param $Debtor
     *
     * @return Stage
     */
    public static function frontendBankingPersonSelect( $Debtor, $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingPersonSelect( $Debtor, $Id );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingDelete ( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingDelete( $Id );
    }

    /**
     * @param $Id
     *
     * @return mixed
     */
    public static function frontendBankingSelectCommodity ( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingSelectCommodity( $Id );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingRemoveCommodity( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingRemoveCommodity( $Id );
    }

    /**
     * @param $Id
     * @param $CommodityId
     *
     * @return Stage
     */
    public static function frontendBankingAddCommodity( $Id, $CommodityId )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingAddCommodity( $Id, $CommodityId );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingSelectReference( $Id, $Reference )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingSelectReference( $Id, $Reference );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingSelectReferenceDelete( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingSelectReferenceDelete( $Id );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingReferenceDeactivate( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingReferenceDeactivate( $Id );
    }

}