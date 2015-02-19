<?php
namespace KREDA\Sphere\Application\System\Module;

use KREDA\Sphere\Application\System\Frontend\Cache as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Cache
 *
 * @package KREDA\Sphere\Application\System\Module
 */
class Cache extends Common
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
            '/Sphere/System/Cache/Status', __CLASS__.'::frontendStatus'
        )->setParameterDefault( 'Clear', null );
    }

    /**
     * @param bool $Clear
     *
     * @return Stage
     */
    public function frontendStatus( $Clear )
    {

        $this->setupModuleNavigation();
        return Frontend::stageStatus( $Clear );
    }
}
