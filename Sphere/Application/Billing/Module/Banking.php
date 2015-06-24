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
            '/Sphere/Billing/Banking/Commodity/Select', __CLASS__.'::frontendBankingCommoditySelect'
        )   ->setParameterDefault( 'Id', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Commodity/Remove', __CLASS__.'::frontendBankingCommodityRemove'
        )   ->setParameterDefault( 'Id', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Commodity/Add', __CLASS__.'::frontendBankingCommodityAdd'
        )   ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'CommodityId', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Reference/Select', __CLASS__.'::frontendBankingReferenceSelect'
        )   ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'Reference', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Reference/Delete', __CLASS__.'::frontendBankingReferenceDelete'
        )   ->setParameterDefault( 'Id', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Reference/Select/Deactivate', __CLASS__.'::frontendBankingReferenceDeactivate'
        )   ->setParameterDefault( 'Id', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Debtor/Edit', __CLASS__.'::frontendBankingDebtorEdit'
        )   ->setParameterDefault( 'Debtor', null )
            ->setParameterDefault( 'Id', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Banking/Debtor/Reference', __CLASS__.'::frontendBankingDebtorReference'
        )   ->setParameterDefault( 'Reference', null )
            ->setParameterDefault( 'Id', null );
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
    public static function frontendBankingCommoditySelect ( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingCommoditySelect( $Id );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingCommodityRemove( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingCommodityRemove( $Id );
    }

    /**
     * @param $Id
     * @param $CommodityId
     *
     * @return Stage
     */
    public static function frontendBankingCommodityAdd( $Id, $CommodityId )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingCommodityAdd( $Id, $CommodityId );
    }

    /**
     * @param $Id
     * @param $Reference
     *
     * @return Stage
     */
    public static function frontendBankingReferenceSelect( $Id, $Reference )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingReferenceSelect( $Id, $Reference );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingReferenceDelete( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingReferenceDelete( $Id );
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

    /**
     * @param $Id
     * @param $Debtor
     *
     * @return Stage
     */
    public static function frontendBankingDebtorEdit( $Id, $Debtor )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingDebtorEdit( $Id, $Debtor );
    }

    /**
     * @param $Id
     * @param $Reference
     *
     * @return Stage
     */
    public static function frontendBankingDebtorReference( $Id, $Reference )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBankingDebtorReference( $Id, $Reference );
    }

}