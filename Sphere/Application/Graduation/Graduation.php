<?php
namespace KREDA\Sphere\Application\Graduation;

use KREDA\Sphere\Application\Graduation\Service\Grade;
use KREDA\Sphere\Application\Graduation\Service\Score;
use KREDA\Sphere\Application\Graduation\Service\Weight;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EducationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TagListIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Graduation
 *
 * @package KREDA\Sphere\Application\Graduation
 */
class Graduation extends AbstractApplication
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

        self::setupApplicationAccess( 'Graduation' );

        self::$Configuration = $Configuration;

        self::addClientNavigationMain( self::$Configuration,
            '/Sphere/Graduation', 'Zensuren', new EducationIcon()
        );

        self::registerClientRoute( self::$Configuration, '/Sphere/Graduation',
            __CLASS__.'::frontendGrade' );
        $Route = self::registerClientRoute( self::$Configuration, '/Sphere/Graduation/Grade/Type',
            __CLASS__.'::frontendGradeType' );
        $Route->setParameterDefault( 'GradeName', null );
        $Route->setParameterDefault( 'GradeAcronym', null );

        return $Configuration;
    }

    /**
     * @return Service\Score
     */
    public static function serviceScore()
    {

        return Score::getApi();
    }

    /**
     * @return Service\Grade
     */
    public static function serviceGrade()
    {

        return Grade::getApi();
    }

    /**
     * @return Service\Weight
     */
    public static function serviceWeight()
    {

        return Weight::getApi();
    }

    /**
     * @return Stage
     */
    public static function frontendGrade()
    {

        self::setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Zensuren' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    /**
     * @return void
     */
    protected static function setupModuleNavigation()
    {

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Graduation/Grade/Type', 'Zensurentypen', new TagListIcon()
        );
    }

    /**
     * @return Stage
     */
    public static function frontendGradeType()
    {

        self::setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Zensuren' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

}
