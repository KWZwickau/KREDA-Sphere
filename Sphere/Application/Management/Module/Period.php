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
            '/Sphere/Management/Period/SchoolYear', __CLASS__.'::frontendSchoolYear'
        )
            ->setParameterDefault( 'Name', null )
            ->setParameterDefault( 'FirstTerm', null )
            ->setParameterDefault( 'SecondTerm', null );
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
     *
     * @return Stage
     */
    public static function frontendSchoolYear( $Name, $FirstTerm, $SecondTerm )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageSchoolYear( $Name, $FirstTerm, $SecondTerm );
    }
}
