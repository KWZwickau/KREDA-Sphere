<?php
namespace KREDA\Sphere\Application\System;

use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class System
 *
 * @package KREDA\Sphere\Application\System
 */
class System extends Module\Consumer
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

        self::setupApplicationAccess( 'System' );
        self::$Configuration = $Configuration;
        /**
         * Navigation
         */
        self::addClientNavigationMeta( self::$Configuration,
            '/Sphere/System', 'System', new CogWheelsIcon()
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/System', __CLASS__.'::frontendSystem'
        );

        Module\Common::registerApplication( $Configuration );
        Module\Cache::registerApplication( $Configuration );
        Module\Database::registerApplication( $Configuration );
        Module\Update::registerApplication( $Configuration );
        Module\Protocol::registerApplication( $Configuration );
        Module\Authorization::registerApplication( $Configuration );
        Module\Consumer::registerApplication( $Configuration );

        return $Configuration;
    }

    /**
     * @return Service\Update
     */
    public static function serviceUpdate()
    {

        return Service\Update::getApi();
    }

    /**
     * @return Service\Protocol
     */
    public static function serviceProtocol()
    {

        return Service\Protocol::getApi();
    }

    /**
     * @return Element|Stage
     */
    public function frontendSystem()
    {

        $this->setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Systemeinstellungen' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );

        ob_start();
        phpinfo();
        $PhpInfo = ob_get_clean();

        $View->setContent(
            '<div id="phpinfo">'.
            preg_replace( '!,!', ', ',
                preg_replace( '!<th>(enabled)\s*</th>!i',
                    '<th><span class="badge badge-success">$1</span></th>',
                    preg_replace( '!<td class="v">(On|enabled|active|Yes)\s*</td>!i',
                        '<td class="v"><span class="badge badge-success">$1</span></td>',
                        preg_replace( '!<td class="v">(Off|disabled|No)\s*</td>!i',
                            '<td class="v"><span class="badge badge-danger">$1</span></td>',
                            preg_replace( '!<i>no value</i>!',
                                '<span class="label label-warning">no value</span>',
                                preg_replace( '%^.*<body>(.*)</body>.*$%ms', '$1', $PhpInfo )
                            )
                        )
                    )
                )
            )
            .'</div>'
        );
        return $View;
    }
}
