<?php
namespace KREDA\Sphere\Application\Management;

use KREDA\Sphere\Application\Management\Service\Education;
use KREDA\Sphere\Application\Management\Service\People;
use KREDA\Sphere\Application\Management\Service\Property;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BookIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BriefcaseIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GearIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\HomeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TagListIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TileBigIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TileListIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TileSmallIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Management
 *
 * @package KREDA\Sphere\Application\Management
 */
class Management extends AbstractApplication
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

        self::getDebugger()->addMethodCall( __METHOD__ );

        self::$Configuration = $Configuration;

        /**
         * Navigation
         */
        self::registerClientRoute( self::$Configuration, '/Sphere/Management', __CLASS__.'::apiMain' );
        self::addClientNavigationMain( self::$Configuration, '/Sphere/Management', 'Verwaltung', new GearIcon() );

        /**
         * Property
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Property', __CLASS__.'::apiProperty'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Property/School', __CLASS__.'::apiPropertySchool'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Property/Building', __CLASS__.'::apiPropertyBuilding'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Property/Room', __CLASS__.'::apiPropertyRoom'
        );

        /**
         * People
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/People', __CLASS__.'::apiPeople'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/People/Staff', __CLASS__.'::apiPeopleStaff'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/People/Staff/Create', __CLASS__.'::apiPeopleStaffCreate'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/People/Student', __CLASS__.'::apiPeopleStudent'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/People/Parent', __CLASS__.'::apiPeopleParent'
        );

        /**
         * Arrangement
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Arrangement', __CLASS__.'::apiArrangement'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Arrangement/Group', __CLASS__.'::apiArrangementGroup'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Arrangement/Subject', __CLASS__.'::apiArrangementSubject'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Arrangement/Period', __CLASS__.'::apiArrangementPeriod'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Arrangement/Mission', __CLASS__.'::apiArrangementMission'
        );

        return $Configuration;
    }

    /**
     * @return Service\Education
     */
    public static function serviceEducation()
    {

        return Education::getApi();
    }

    public function apiMain()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $View = new Landing();
        $View->setTitle( 'Verwaltung' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    protected function setupModuleNavigation()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Property', 'Immobilien', new HomeIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/People', 'Personen', new PersonIcon()
        );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Arrangement/Group', 'Klassen', new TagListIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Arrangement/Subject', 'Fächer', new BookIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Arrangement/Period', 'Zeiten', new TimeIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Arrangement/Mission', 'Aufträge', new BriefcaseIcon()
        );
    }

    public function apiProperty()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $this->setupServiceProperty();
        return Property::getApi( '/Sphere/Management/Property' )->apiMain();
    }

    private function setupServiceProperty()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Property/School', 'Schulen', new TileBigIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Property/Building', 'Gebäude', new TileSmallIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Property/Room', 'Räume', new TileListIcon()
        );
    }

    public function apiPropertySchool()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $this->setupServiceProperty();
        return Property::getApi( '/Sphere/Management/Property' )->apiMain();
    }

    public function apiPropertyBuilding()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $this->setupServiceProperty();
        return Property::getApi( '/Sphere/Management/Property' )->apiMain();
    }

    public function apiPropertyRoom()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $this->setupServiceProperty();
        return Property::getApi( '/Sphere/Management/Property' )->apiMain();
    }

    public function apiPeople()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $this->setupServicePeople();
        return People::getApi( '/Sphere/Management/People' )->apiMain();
    }

    private function setupServicePeople()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/People/Staff', 'Personal', new PersonIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/People/Student', 'Schüler', new PersonIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/People/Parent', 'Eltern', new PersonIcon()
        );

    }

    public function apiPeopleStaff()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $this->setupServicePeople();
        return People::getApi( '/Sphere/Management/People/Staff' )->apiPeopleStaff();

    }

    public function apiPeopleStaffCreate()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $this->setupServicePeople();
        return People::getApi( '/Sphere/Management/People/Staff' )->apiPeopleStaffCreate();

    }

    public function apiPeopleStudent()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $this->setupServicePeople();
        return People::getApi( '/Sphere/Management/People/Student' )->apiPeopleStudent();

    }

    public function apiPeopleParent()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setupModuleNavigation();
        $this->setupServicePeople();
        return People::getApi( '/Sphere/Management/People/Parent' )->apiPeopleParent();

    }
}
