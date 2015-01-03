<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRight;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\EntityAction;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Access
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service
 */
class Access extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    private static $AccessCache = array();

    /**
     * @throws \Exception
     */
    public function __construct()
    {

        $this->setDatabaseHandler( 'Gatekeeper', 'Access' );
    }

    public function setupDatabaseContent()
    {

        /**
         * System:Database
         */
        $Privilege = $this->actionCreatePrivilege( 'Application::System:Database' );

        $Right = $this->actionCreateRight( '/Sphere/System/Database' );
        $this->actionAddPrivilegeRight( $Privilege, $Right );
        $Right = $this->actionCreateRight( '/Sphere/System/Database/Status' );
        $this->actionAddPrivilegeRight( $Privilege, $Right );

        $Access = $this->actionCreateAccess( 'Administrator::GodMode' );
        $this->actionAddAccessPrivilege( $Access, $Privilege );

        /**
         * System:Token
         */
        $Privilege = $this->actionCreatePrivilege( 'Application::System:Token' );

        $Right = $this->actionCreateRight( '/Sphere/System/Token/Certification' );
        $this->actionAddPrivilegeRight( $Privilege, $Right );

        $Access = $this->actionCreateAccess( 'Administrator::GodMode' );
        $this->actionAddAccessPrivilege( $Access, $Privilege );

    }

    /**
     * @return Table
     */
    public function schemaTableAccess()
    {

        return $this->getTableAccess();
    }

    /**
     * @param string $Route
     *
     * @return bool
     */
    public function checkIsValidAccess( $Route )
    {

        if (in_array( $Route, self::$AccessCache )) {
            return true;
        }

        try {
            if (false !== ( $Right = $this->entityAccessRightByRouteName( $Route ) )) {
                if (false !== ( $this->entityRightById( $Right->getId() ) )) {
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
     * @return bool|TblAccessRight
     */
    public function entityRightById( $Id )
    {

        return parent::entityRightById( $Id );
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
    public function entityPrivilegeById( $Id )
    {

        return parent::entityPrivilegeById( $Id );
    }
}
