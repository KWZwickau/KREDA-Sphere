<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Management\Frontend\Company as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Company
 *
 * @package KREDA\Sphere\Application\Management\Module
 */
class Company extends Common
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {
        self::$Configuration = $Configuration;

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Company', __CLASS__.'::frontendCompanyList'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Company/Create', __CLASS__.'::frontendCompanyCreate'
        )
            ->setParameterDefault('Company', null);
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Company/Destroy', __CLASS__.'::frontendCompanyDestroy'
        )
            ->setParameterDefault('Id', null);
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Company/Edit', __CLASS__.'::frontendCompanyEdit'
        )
            ->setParameterDefault('Id', null);
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Company/Basic/Edit', __CLASS__.'::frontendCompanyBasicEdit'
        )
            ->setParameterDefault('Id', null)
            ->setParameterDefault('Company', null);
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Company/Address/Edit', __CLASS__.'::frontendCompanyAddressEdit'
        )
            ->setParameterDefault('Id', null)
            ->setParameterDefault('State', null)
            ->setParameterDefault('City', null)
            ->setParameterDefault('Street', null);
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

    }

    /**
     * @return Stage
     */
    public static function frontendCompanyList()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendCompanyList();
    }

    /**
     * @param $Company
     *
     * @return Stage
     */
    public static function frontendCompanyCreate($Company)
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendCompanyCreate($Company);
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendCompanyDestroy($Id)
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendCompanyDestroy($Id);
    }

    /**
 * @param $Id
 * @param $Company
 *
 * @return Stage
 */
    public static function frontendCompanyBasicEdit($Id, $Company)
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendCompanyBasicEdit($Id, $Company);
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendCompanyEdit($Id)
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendCompanyEdit($Id);
    }

    /**
     * @param $Id
     * @param $State
     * @param $City
     * @param $Street
     *
     * @return Stage
     */
    public static function frontendCompanyAddressEdit($Id, $State, $City, $Street)
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendCompanyAddressEdit( $Id, $State, $City, $Street );
    }
}
