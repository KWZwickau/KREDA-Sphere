<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Management\Frontend\Period as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EducationIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Period
 *
 * @package KREDA\Sphere\Application\Management\Module
 */
class Period extends Common
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
            '/Sphere/Management/Period', __CLASS__.'::frontendPeriod'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Period/SchoolYear', __CLASS__.'::frontendSchoolYearCreate'
        )
            ->setParameterDefault( 'Name', null )
            ->setParameterDefault( 'FirstTerm', null )
            ->setParameterDefault( 'SecondTerm', null )
            ->setParameterDefault( 'Course', null );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Period/SchoolYear/Edit', __CLASS__.'::frontendSchoolYearEdit'
        )
            ->setParameterDefault( 'Name', null )
            ->setParameterDefault( 'FirstTerm', null )
            ->setParameterDefault( 'SecondTerm', null )
            ->setParameterDefault( 'Course', null );
    }

    /**
     * @return Stage
     */
    public static function frontendPeriod()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        $View = new Stage();
        $View->setTitle( 'Zeiten' );
        return $View;
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Period/SchoolYear', 'Schuljahre', new EducationIcon()
        );
    }

    /**
     * @param null|string $Name
     * @param null|array  $FirstTerm
     * @param null|array  $SecondTerm
     * @param null|int    $Course
     *
     * @return Stage
     */
    public static function frontendSchoolYearCreate( $Name, $FirstTerm, $SecondTerm, $Course )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageSchoolYearCreate( $Name, $FirstTerm, $SecondTerm, $Course );
    }

    /**
     * @param null|int    $Id
     * @param null|string $Name
     * @param null|array  $FirstTerm
     * @param null|array  $SecondTerm
     * @param null|int    $Course
     *
     * @return Stage
     */
    public static function frontendSchoolYearEdit( $Id, $Name, $FirstTerm, $SecondTerm, $Course )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageSchoolYearEdit( $Id, $Name, $FirstTerm, $SecondTerm, $Course );
    }
}
