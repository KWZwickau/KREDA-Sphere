<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Management\Frontend\Education as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
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
            '/Sphere/Management/Education/Composition', __CLASS__.'::frontendEducationComposition'
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
            '/Sphere/Management/Education/Subject', 'Fächer', new CogWheelsIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Education/Group', 'Klassen', new CogWheelsIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Education/Composition', 'Fach-Klassen', new CogWheelsIcon()
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
    public static function frontendEducationComposition()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageComposition();
    }
}
