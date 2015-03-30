<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Management\Frontend\Education as Frontend;
use KREDA\Sphere\Application\Management\Frontend\Education\Group;
use KREDA\Sphere\Application\Management\Frontend\Education\Setup;
use KREDA\Sphere\Application\Management\Frontend\Education\Subject;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ShareIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Education
 *
 * @package KREDA\Sphere\Application\Management\Module
 */
class Education extends Campus
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
            '/Sphere/Management/Education', __CLASS__.'::frontendEducation'
        );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Setup', __CLASS__.'::frontendEducationSetup'
        )
            ->setParameterDefault( 'Term', null )
            ->setParameterDefault( 'Level', null )
            ->setParameterDefault( 'Group', null )
            ->setParameterDefault( 'Subject', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Group', __CLASS__.'::frontendGroup'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Subject', __CLASS__.'::frontendSubject'
        );
//
//        self::registerClientRoute( self::$Configuration,
//            '/Sphere/Management/Education/Period', __CLASS__.'::frontendPeriod'
//        );
//        self::registerClientRoute( self::$Configuration,
//            '/Sphere/Management/Education/Mission', __CLASS__.'::frontendMission'
//        );
    }

    /**
     * @return Stage
     */
    public static function frontendEducation()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageEducation();
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Education/Group', 'Klassen', new ShareIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Education/Subject', 'Fächer', new ShareIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Education/Setup', 'Einstellungen', new CogWheelsIcon()
        );
    }

    /**
     * @param null|array $Term
     * @param null|array $Level
     * @param null|array $Group
     * @param null|array $Subject
     *
     * @return Stage
     */
    public static function frontendEducationSetup( $Term, $Level, $Group, $Subject )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Setup::stageSetup( $Term, $Level, $Group, $Subject );
    }

    /**
     * @return Stage
     */
    public static function frontendGroup()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Group::stageGroup();
    }

    /**
     * @return Stage
     */
    public static function frontendSubject()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Subject::stageSubject();
    }
}
