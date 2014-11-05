<?php
namespace KREDA\Sphere\Application\Management;

use KREDA\Sphere\Application\Application;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelApplication;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelClient;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelModule;
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

class Client extends Application
{

    /** @var Configuration $Config */
    private static $Configuration = null;
    /** @var \KREDA\Sphere\Application\Management\Service\People $ServicePeople */
    private static $ServicePeople = null;

    /**
     * @param Configuration $Configuration
     *
     * @return Configuration
     */
    public static function setupApi( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
        self::$ServicePeople = new Service\People();

        self::buildNavigationMain( self::$Configuration,
            '/Sphere/Management', 'Verwaltung', new GearIcon()
        );
        self::buildRoute( self::$Configuration,
            '/Sphere/Management', __CLASS__.'::apiMain'
        );

        self::buildRoute( self::$Configuration,
            '/Sphere/Management/Property', __CLASS__.'::apiProperty'
        );
        self::buildRoute( self::$Configuration,
            '/Sphere/Management/Property/School', __CLASS__.'::apiPropertySchool'
        );
        self::buildRoute( self::$Configuration,
            '/Sphere/Management/Property/Building', __CLASS__.'::apiPropertyBuilding'
        );
        self::buildRoute( self::$Configuration,
            '/Sphere/Management/Property/Room', __CLASS__.'::apiPropertyRoom'
        );

        self::buildRoute( self::$Configuration,
            '/Sphere/Management/People', __CLASS__.'::apiPeople'
        );
        self::buildRoute( self::$Configuration,
            '/Sphere/Management/People/Staff', __CLASS__.'::apiPeopleStaff'
        );
        self::buildRoute( self::$Configuration,
            '/Sphere/Management/People/Student', __CLASS__.'::apiPeopleStudent'
        );
        self::buildRoute( self::$Configuration,
            '/Sphere/Management/People/Parent', __CLASS__.'::apiPeopleParent'
        );

        self::buildRoute( self::$Configuration,
            '/Sphere/Management/Arrangement', __CLASS__.'::apiArrangement'
        );
        self::buildRoute( self::$Configuration,
            '/Sphere/Management/Arrangement/Group', __CLASS__.'::apiArrangementGroup'
        );
        self::buildRoute( self::$Configuration,
            '/Sphere/Management/Arrangement/Subject', __CLASS__.'::apiArrangementSubject'
        );
        self::buildRoute( self::$Configuration,
            '/Sphere/Management/Arrangement/Period', __CLASS__.'::apiArrangementPeriod'
        );
        self::buildRoute( self::$Configuration,
            '/Sphere/Management/Arrangement/Mission', __CLASS__.'::apiArrangementMission'
        );

        return $Configuration;
    }

    public function apiMain()
    {

        $this->setupModule();
        $View = new Landing();
        $View->setTitle( 'Verwaltung' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function setupModule()
    {

        self::buildModuleMain( self::$Configuration,
            '/Sphere/Management/Property', 'Immobilien', new HomeIcon()
        );

        self::buildModuleMain( self::$Configuration,
            '/Sphere/Management/People', 'Personen', new PersonIcon()
        );

        self::buildModuleMain( self::$Configuration,
            '/Sphere/Management/Arrangement/Group', 'Klassen', new TagListIcon()
        );
        self::buildModuleMain( self::$Configuration,
            '/Sphere/Management/Arrangement/Subject', 'Fächer', new BookIcon()
        );
        self::buildModuleMain( self::$Configuration,
            '/Sphere/Management/Arrangement/Period', 'Zeiten', new TimeIcon()
        );
        self::buildModuleMain( self::$Configuration,
            '/Sphere/Management/Arrangement/Mission', 'Aufträge', new BriefcaseIcon()
        );
    }

    public function apiProperty()
    {

        $this->setupModule();
        $this->setupMenuProperty();
        $View = new Landing();
        $View->setTitle( 'Immobilien' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    private function setupMenuProperty()
    {

        self::buildMenuMain( self::$Configuration,
            '/Sphere/Management/Property/School', 'Schulen', new TileBigIcon()
        );
        self::buildMenuMain( self::$Configuration,
            '/Sphere/Management/Property/Building', 'Gebäude', new TileSmallIcon()
        );
        self::buildMenuMain( self::$Configuration,
            '/Sphere/Management/Property/Room', 'Räume', new TileListIcon()
        );
    }

    public function apiPropertySchool()
    {

        $this->setupModule();
        $this->setupMenuProperty();
        $View = new Landing();
        $View->setTitle( 'Immobilien' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function apiPropertyBuilding()
    {

        $this->setupModule();
        $this->setupMenuProperty();
        $View = new Landing();
        $View->setTitle( 'Immobilien' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function apiPropertyRoom()
    {

        $this->setupModule();
        $this->setupMenuProperty();
        $View = new Landing();
        $View->setTitle( 'Immobilien' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function apiPeople()
    {

        $this->setupModule();
        $this->setupMenuPeople();
        return self::$ServicePeople->apiPeople();
    }

    private function setupMenuPeople()
    {

        self::buildMenuMain( self::$Configuration,
            '/Sphere/Management/People/Staff', 'Personal', new PersonIcon()
        );
        self::buildMenuMain( self::$Configuration,
            '/Sphere/Management/People/Student', 'Schüler', new PersonIcon()
        );
        self::buildMenuMain( self::$Configuration,
            '/Sphere/Management/People/Parent', 'Eltern', new PersonIcon()
        );

    }

    public function apiPeopleStaff()
    {

        $this->setupModule();
        $this->setupMenuPeople();
        return self::$ServicePeople->apiPeopleStaff();
    }

    public function apiPeopleStudent()
    {

        $this->setupModule();
        $this->setupMenuPeople();
        return self::$ServicePeople->apiPeopleStudent();
    }

    public function apiPeopleParent()
    {

        $this->setupModule();
        $this->setupMenuPeople();
        return self::$ServicePeople->apiPeopleParent();
    }

}
