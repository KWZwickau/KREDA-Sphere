<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\System\Frontend\Authorization\Access\Access;
use KREDA\Sphere\Application\System\Frontend\Authorization\Privilege\Privilege;
use KREDA\Sphere\Application\System\Frontend\Authorization\Right\Right;
use KREDA\Sphere\Application\System\Frontend\Authorization\Role\Role;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Authorization
 *
 * @package KREDA\Sphere\Application\System\Frontend\Authorization
 */
class Authorization extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageAuthorization()
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Überblick' );
        $View->setMessage( 'Zeigt die aktuell verfügbaren Berechtigungen' );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageAuthorizationRight()
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Rechte' );
        $View->setMessage( 'Zeigt die aktuell verfügbaren Rechte' );
        $View->setContent(
            new Right( Gatekeeper::serviceAccess()->entityRightAll() )
        );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageAuthorizationPrivilege()
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Privilegien' );
        $View->setMessage( 'Zeigt die aktuell verfügbaren Privilegien' );
        $View->setContent(
            new Privilege( Gatekeeper::serviceAccess()->entityPrivilegeAll() )
        );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageAuthorizationAccess()
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Zugriffslevel' );
        $View->setMessage( 'Zeigt die aktuell verfügbaren Zugriffslevel' );
        $View->setContent(
            new Access( Gatekeeper::serviceAccess()->entityAccessAll() )
        );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageAuthorizationRole()
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Rollen' );
        $View->setMessage( 'Zeigt die aktuell verfügbaren Rollen' );
        $View->setContent(
            new Role( Gatekeeper::serviceAccount()->entityAccountRoleAll() )
        );
        return $View;
    }

}
