<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Management\Frontend\Person as Frontend;
use KREDA\Sphere\Application\Management\Management;
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
            '/Sphere/Management/Person/Edit', __CLASS__.'::frontendEdit'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'PersonName', null )
            ->setParameterDefault( 'BirthDetail', null )
            ->setParameterDefault( 'PersonInformation', null )
            ->setParameterDefault( 'State', null )
            ->setParameterDefault( 'City', null )
            ->setParameterDefault( 'Street', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Destroy', __CLASS__.'::frontendDestroy'
        )
            ->setParameterDefault( 'Id', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/List/Student', __CLASS__.'::frontendListStudent'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/List/Interest', __CLASS__.'::frontendListInterest'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/List/Guardian', __CLASS__.'::frontendListGuardian'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/List/Teacher', __CLASS__.'::frontendListTeacher'
        );

        /**
         * REST Service
         */
        self::registerClientRoute( self::$Configuration, '/Sphere/Management/Table/PersonInterest',
            __CLASS__.'::restPersonListByType' )
            ->setParameterDefault( 'tblPersonType',
                Management::servicePerson()->entityPersonTypeByName( 'Interessent' )->getId()
            );
        self::registerClientRoute( self::$Configuration, '/Sphere/Management/Table/PersonStudent',
            __CLASS__.'::restPersonListByType' )
            ->setParameterDefault( 'tblPersonType',
                Management::servicePerson()->entityPersonTypeByName( 'Schüler' )->getId()
            );
        self::registerClientRoute( self::$Configuration, '/Sphere/Management/Table/PersonGuardian',
            __CLASS__.'::restPersonListByType' )
            ->setParameterDefault( 'tblPersonType',
                Management::servicePerson()->entityPersonTypeByName( 'Sorgeberechtigter' )->getId()
            );
        self::registerClientRoute( self::$Configuration, '/Sphere/Management/Table/PersonTeacher',
            __CLASS__.'::restPersonListByType' )
            ->setParameterDefault( 'tblPersonType',
                Management::servicePerson()->entityPersonTypeByName( 'Lehrer' )->getId()
            );
    }

    /**
     * @param int $tblPersonType
     */
    public static function restPersonListByType( $tblPersonType )
    {

        $tblPersonType = Management::servicePerson()->entityPersonTypeById( $tblPersonType );
        print Management::servicePerson()->tablePersonAllByType( $tblPersonType );
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
            '/Sphere/Management/Person/List/Interest', 'Interessenten', new GroupIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Person/List/Student', 'Schüler', new GroupIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Person/List/Guardian', 'Sorgeberechtigte', new GroupIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Person/List/Teacher', 'Lehrer', new GroupIcon()
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
     * @param null|integer   $Id
     * @param null|array     $PersonName
     * @param null|array     $PersonInformation
     * @param null|array     $BirthDetail
     *
     *
     * @param null|int|array $State
     * @param null|array     $City
     * @param null|array     $Street
     *
     * @return Stage
     */
    public static function frontendEdit( $Id, $PersonName, $PersonInformation, $BirthDetail, $State, $City, $Street )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageEdit( $Id, $PersonName, $PersonInformation, $BirthDetail, $State, $City, $Street );
    }

    /**
     * @param null|integer $Id
     *
     * @return Stage
     */
    public static function frontendDestroy( $Id )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageDestroy( $Id );
    }

    /**
     * @return Stage
     */
    public static function frontendListStudent()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend\ListTable::stageListStudent();
    }

    /**
     * @return Stage
     */
    public static function frontendListInterest()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend\ListTable::stageListInterest();
    }

    /**
     * @return Stage
     */
    public static function frontendListGuardian()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend\ListTable::stageListGuardian();
    }

    /**
     * @return Stage
     */
    public static function frontendListTeacher()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend\ListTable::stageListTeacher();
    }
}
