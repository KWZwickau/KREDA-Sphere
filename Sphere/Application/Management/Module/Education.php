<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Management\Frontend\Education as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EducationIcon;
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
            '/Sphere/Management/Education/Subject', __CLASS__.'::frontendEducationSubject'
        )
            ->setParameterDefault( 'Subject', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Subject/Category', __CLASS__.'::frontendEducationSubjectCategory'
        );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Group', __CLASS__.'::frontendEducationGroup'
        )
            ->setParameterDefault( 'Level', null )
            ->setParameterDefault( 'Group', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Session', __CLASS__.'::frontendEducationSubjectGroup'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Student', __CLASS__.'::frontendEducationSubjectGroupStudent'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Teacher', __CLASS__.'::frontendEducationSubjectGroupTeacher'
        );
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
            '/Sphere/Management/Education/Subject', 'Schulfächer', new CogWheelsIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Education/Group', 'Schulklassen', new CogWheelsIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Education/Session', 'Unterrichtsfächer', new EducationIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Education/Student', 'Unterrichtsgruppen', new EducationIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Education/Teacher', 'Lehraufträge', new EducationIcon()
        );
    }

    /**
     * @param null|array $Subject
     *
     * @return Stage
     */
    public static function frontendEducationSubject( $Subject )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageSubject( $Subject );
    }

    /**
     * @return Stage
     */
    public static function frontendEducationSubjectCategory()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageSubjectCategory();
    }

    /**
     * @param null|array $Level
     * @param null|array $Group
     *
     * @return Stage
     */
    public static function frontendEducationGroup( $Level, $Group )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageGroup( $Level, $Group );
    }

    /**
     * @return Stage
     */
    public static function frontendEducationSubjectGroup()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageSubjectGroup();
    }

    /**
     * @return Stage
     */
    public static function frontendEducationSubjectGroupStudent()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageSubjectGroupStudent();
    }

    /**
     * @return Stage
     */
    public static function frontendEducationSubjectGroupTeacher()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageSubjectGroupTeacher();
    }
}
