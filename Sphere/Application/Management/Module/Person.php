<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Management\Frontend\Person as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Person
 *
 * @package KREDA\Sphere\Application\Management\Module
 */
class Person extends Account
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;

        /**
         * Person
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person', __CLASS__.'::frontendStatus'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Create', __CLASS__.'::frontendCreate'
        )
            ->setParameterDefault( 'PersonName', null )
            ->setParameterDefault( 'BirthDetail', null )
            ->setParameterDefault( 'PersonInformation', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/List/Student', __CLASS__.'::frontendListStudent'
        );

    }

    /**
     * @return Stage
     */
    public static function frontendStatus()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageStatus();
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Person/Create', 'Person anlegen', new PersonIcon()
        );

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Person/List/Interest', 'Interessend', new GroupIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Person/List/Student', 'Schüler', new GroupIcon()
        );

    }

    /**
     * @param null|array $PersonName
     * @param null|array $PersonInformation
     * @param null|array $BirthDetail
     *
     * @return Stage
     */
    public static function frontendCreate( $PersonName, $PersonInformation, $BirthDetail )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageCreate( $PersonName, $PersonInformation, $BirthDetail );
    }

    /**
     * @return Stage
     */
    public static function frontendListStudent()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageListStudent();
    }
}
