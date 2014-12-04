<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\EntityAction;

/**
 * Class Access
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service
 */
class Access extends EntityAction
{

    private static $AccessCache = array();

    /**
     * @throws \Exception
     */
    public function __construct()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->connectDatabase( 'Gatekeeper-Access' );
    }

    public function setupDatabaseContent()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        /**
         * System:Database
         */
        $Privilege = $this->actionCreateAccessPrivilege( 'Application::System:Database' );

        $Right = $this->actionCreateAccessRight( '/Sphere/System/Database' );
        $this->actionCreateAccessRightPrivilegeList( $Right, $Privilege );
        $Right = $this->actionCreateAccessRight( '/Sphere/System/Database/Status' );
        $this->actionCreateAccessRightPrivilegeList( $Right, $Privilege );

        $Role = $this->actionCreateAccessRole( 'Administrator::GodMode' );
        $this->actionCreateAccessPrivilegeRoleList( $Privilege, $Role );

        /**
         * System:Token
         */
        $Privilege = $this->actionCreateAccessPrivilege( 'Application::System:Token' );

        $Right = $this->actionCreateAccessRight( '/Sphere/System/Token/Certification' );
        $this->actionCreateAccessRightPrivilegeList( $Right, $Privilege );

        $Role = $this->actionCreateAccessRole( 'Administrator::GodMode' );
        $this->actionCreateAccessPrivilegeRoleList( $Privilege, $Role );

    }

    /**
     * @return Table
     */
    public function schemaTableAccessRole()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->getTableAccessRole();
    }

    /**
     * @param $Route
     *
     * @return bool
     */
    public function apiIsValidAccess( $Route )
    {

        $this->getDebugger()->addMethodCall( __METHOD__.':'.$Route );

        if (in_array( $Route, self::$AccessCache )) {
            return true;
        }

        try {
            if (false !== ( $Right = $this->entityAccessRightByRouteName( $Route ) )) {
                if (false !== ( $this->entityViewAccessByAccessRight( $Right ) )) {
                    self::$AccessCache[] = $Route;
                    return true;
                }
            }
        } catch( \Exception $E ) {

        }
        return false;
    }
}
