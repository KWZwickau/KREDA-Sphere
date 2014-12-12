<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRight;
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

        $this->setDatabaseHandler( 'Gatekeeper', 'Access' );
    }

    public function setupDatabaseContent()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        /**
         * System:Database
         */
        $Privilege = $this->actionCreateAccessPrivilege( 'Application::System:Database' );

        $Right = $this->actionCreateAccessRight( '/Sphere/System/Database' );
        $this->actionCreateAccessRightList( $Right, $Privilege );
        $Right = $this->actionCreateAccessRight( '/Sphere/System/Database/Status' );
        $this->actionCreateAccessRightList( $Right, $Privilege );

        $Role = $this->actionCreateAccess( 'Administrator::GodMode' );
        $this->actionCreateAccessPrivilegeList( $Privilege, $Role );

        /**
         * System:Token
         */
        $Privilege = $this->actionCreateAccessPrivilege( 'Application::System:Token' );

        $Right = $this->actionCreateAccessRight( '/Sphere/System/Token/Certification' );
        $this->actionCreateAccessRightList( $Right, $Privilege );

        $Role = $this->actionCreateAccess( 'Administrator::GodMode' );
        $this->actionCreateAccessPrivilegeList( $Privilege, $Role );

    }

    /**
     * @return Table
     */
    public function schemaTableAccess()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->getTableAccess();
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

    /**
     * @param integer $Id
     *
     * @return bool|TblAccess
     */
    public function entityAccessById( $Id )
    {

        return parent::entityAccessById( $Id );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccessPrivilege
     */
    public function entityAccessPrivilegeById( $Id )
    {

        return parent::entityAccessPrivilegeById( $Id );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccessRight
     */
    public function entityAccessRightById( $Id )
    {

        return parent::entityAccessRightById( $Id );
    }
}
