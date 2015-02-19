<?php
namespace KREDA\Sphere\Application\System\Module;

use KREDA\Sphere\Application\System\Frontend\Protocol as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Protocol
 *
 * @package KREDA\Sphere\Application\System\Module
 */
class Protocol extends Update
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
            '/Sphere/System/Protocol/Status', __CLASS__.'::frontendStatus'
        );
    }

    /**
     * @return Stage
     */
    public function frontendStatus()
    {

        $this->setupModuleNavigation();
        return Frontend::stageStatus();
    }
}
