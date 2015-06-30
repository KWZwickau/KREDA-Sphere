<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Management\Frontend\Group as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Group
 *
 * @package KREDA\Sphere\Application\Management\Module
 */
class Group extends Common
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
            '/Sphere/Management/Group', __CLASS__.'::frontendGroup'
        )
            ->setParameterDefault( 'Group', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Group/Destroy', __CLASS__.'::frontendGroupDestroy'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Group/Edit', __CLASS__.'::frontendGroupEdit'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Group/Member', __CLASS__.'::frontendGroupMember'
        )
            ->setParameterDefault( 'Id', null );

    }

    /**
     * @param null|array $Group
     *
     * @return Stage
     */
    public static function frontendGroup( $Group )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendGroup( $Group );
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

    }
}
