<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Management\Frontend\Person as Frontend;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\PersonalData;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
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

    }

    /**
     * @return Stage
     */
    public static function frontendStatus()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return PersonalData::stagePerson();
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Person/Create', 'Person anlegen', new PersonIcon()
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

}
