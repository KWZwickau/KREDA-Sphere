<?php
namespace KREDA\Sphere\Application\Management;

use KREDA\Sphere\Application\Management\Service\Education;
use KREDA\Sphere\Application\Management\Service\Person;
use KREDA\Sphere\Application\Management\Subject\Subject;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BookIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BriefcaseIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GearIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\HomeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TagListIcon;
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
         * Education
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education', __CLASS__.'::guiEducation'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Group', __CLASS__.'::guiEducationGroup'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Subject', __CLASS__.'::guiEducationSubject'
        );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Period', __CLASS__.'::guiEducationPeriod'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Mission', __CLASS__.'::guiEducationMission'
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

    /**
     * @return Service\Person
     */
    public static function servicePerson()
    {

        return Person::getApi();
    }

    /**
     * @return Stage
     */
    public function guiEducationSubject()
    {

        $this->setupModuleNavigation();
        return Subject::guiSubject();
    }

    protected function setupModuleNavigation()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Campus', 'Immobilien', new HomeIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Person', 'Personen', new PersonIcon()
        );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Education/Group', 'Klassen', new TagListIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Education/Subject', 'Fächer', new BookIcon()
        );

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Education/Period', 'Zeiten', new TimeIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Management/Education/Mission', 'Aufträge', new BriefcaseIcon()
        );
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
}
