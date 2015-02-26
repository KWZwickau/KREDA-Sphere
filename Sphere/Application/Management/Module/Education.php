<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Management\Frontend\Subject\Subject;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
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
     *
     * @return Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education', __CLASS__.'::frontendEducation'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Group', __CLASS__.'::frontendGroup'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Subject', __CLASS__.'::frontendSubject'
        );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Period', __CLASS__.'::frontendPeriod'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Mission', __CLASS__.'::frontendMission'
        );
    }

    /**
     * @return Stage
     */
    public static function frontendSubject()
    {

        self::setupModuleNavigation();
        return Subject::guiSubject();
    }
}
