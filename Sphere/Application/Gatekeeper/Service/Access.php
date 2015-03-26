<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilegeList;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRight;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRightList;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\EntityAction;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Common\Frontend\Form\AbstractForm;
use KREDA\Sphere\Common\Frontend\Redirect;

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
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Gatekeeper', 'Access' );
    }

    public function setupDatabaseContent()
    {

        $Access = $this->actionCreateAccess( 'System:Administrator' );
        $Privilege = $this->actionCreatePrivilege( 'System:Administrator' );
        $Right = $this->actionCreateRight( 'Application:System' );
        $this->actionAddPrivilegeRight( $Privilege, $Right );
        $this->actionAddAccessPrivilege( $Access, $Privilege );

        $Access = $this->actionCreateAccess( 'Management:Administrator' );
        $Privilege = $this->actionCreatePrivilege( 'Management:Administrator' );
        $Right = $this->actionCreateRight( 'Application:Management' );
        $this->actionAddPrivilegeRight( $Privilege, $Right );
        $this->actionAddAccessPrivilege( $Access, $Privilege );
        $Right = $this->actionCreateRight( '/Sphere/Management/Token' );
        $this->actionAddPrivilegeRight( $Privilege, $Right );
        $Right = $this->actionCreateRight( '/Sphere/Management/Account' );
        $this->actionAddPrivilegeRight( $Privilege, $Right );

        $Access = $this->actionCreateAccess( 'Gatekeeper:MyAccount' );
        $Privilege = $this->actionCreatePrivilege( 'Gatekeeper:MyAccount' );
        $Right = $this->actionCreateRight( '/Sphere/Gatekeeper/MyAccount' );
        $this->actionAddPrivilegeRight( $Privilege, $Right );
        $Right = $this->actionCreateRight( '/Sphere/Gatekeeper/MyAccount/ChangePassword' );
        $this->actionAddPrivilegeRight( $Privilege, $Right );
        $this->actionAddAccessPrivilege( $Access, $Privilege );

        $Access = $this->actionCreateAccess( 'Gatekeeper:MyAccount:View' );
        $Privilege = $this->actionCreatePrivilege( 'Gatekeeper:MyAccount:View' );
        $Right = $this->actionCreateRight( '/Sphere/Gatekeeper/MyAccount' );
        $this->actionAddPrivilegeRight( $Privilege, $Right );
        $this->actionAddAccessPrivilege( $Access, $Privilege );

        $Access = $this->actionCreateAccess( 'Gatekeeper:MyAccount:System' );
        $Privilege = $this->actionCreatePrivilege( 'Gatekeeper:MyAccount:System' );
        $Right = $this->actionCreateRight( '/Sphere/Gatekeeper/MyAccount/ChangeConsumer' );
        $this->actionAddPrivilegeRight( $Privilege, $Right );
        $this->actionAddAccessPrivilege( $Access, $Privilege );
    }

    /**
     * @param string $ApplicationName
     *
     * @return TblAccessRight
     */
    public function executeCreateApplicationRight( $ApplicationName )
    {

        return $this->actionCreateRight( 'Application:'.$ApplicationName );
    }

    /**
     * @param AbstractForm $View
     * @param null|string  $ApplicationRoute
     *
     * @return TblAccessRight
     */
    public function executeCreateApplicationRoute( AbstractForm &$View = null, $ApplicationRoute )
    {

        if (null !== $ApplicationRoute && empty( $ApplicationRoute )) {
            $View->setError( 'Access', 'Bitte geben Sie eine gültige Route ein' );
        } elseif (null !== $ApplicationRoute) {
            $this->actionCreateRight( $ApplicationRoute );
            $View->setSuccess( 'Access', 'Route wurde angelegt' );
        }
        return $View;
    }

    /**
     * @param string $ApplicationName
     * @param string $PrivilegeName
     *
     * @return TblAccessPrivilege
     */
    public function executeCreateApplicationPrivilege( $ApplicationName, $PrivilegeName )
    {

        return $this->actionCreatePrivilege( $ApplicationName.':'.$PrivilegeName );
    }

    /**
     * @param string $ApplicationName
     * @param string $AccessName
     *
     * @return TblAccess
     */
    public function executeCreateApplicationAccess( $ApplicationName, $AccessName )
    {

        return $this->actionCreateAccess( $ApplicationName.':'.$AccessName );
    }

    /**
     * @param AbstractForm $View
     * @param string       $AccessName
     *
     * @return AbstractForm|Redirect
     */
    public function executeCreateAccess( AbstractForm &$View, $AccessName )
    {

        if (null !== $AccessName && empty( $AccessName )) {
            $View->setError( 'AccessName', 'Bitte geben Sie einen Namen ein' );
        }
        if (!empty( $AccessName )) {
            $View->setSuccess( 'AccessName', 'Der Zugriffslevel wurde hinzugefügt' );
            $this->actionCreateAccess( $AccessName );
            return new Redirect( '/Sphere/System/Authorization/Access', 0 );
        }
        return $View;
    }

    /**
     * @param AbstractForm $View
     * @param string       $PrivilegeName
     *
     * @return AbstractForm|Redirect
     */
    public function executeCreatePrivilege( AbstractForm &$View, $PrivilegeName )
    {

        if (null !== $PrivilegeName && empty( $PrivilegeName )) {
            $View->setError( 'PrivilegeName', 'Bitte geben Sie einen Namen ein' );
        }
        if (!empty( $PrivilegeName )) {
            $View->setSuccess( 'PrivilegeName', 'Das Privileg wurde hinzugefügt' );
            $this->actionCreatePrivilege( $PrivilegeName );
            return new Redirect( '/Sphere/System/Authorization/Privilege', 0 );
        }
        return $View;
    }

    /**
     * @param AbstractForm $View
     * @param string       $RightName
     *
     * @return AbstractForm|Redirect
     */
    public function executeCreateRight( AbstractForm &$View, $RightName )
    {

        if (null !== $RightName && empty( $RightName )) {
            $View->setError( 'RightName', 'Bitte geben Sie einen Namen ein' );
        }
        if (!empty( $RightName )) {
            $View->setSuccess( 'RightName', 'Das Recht wurde hinzugefügt' );
            $this->actionCreateRight( $RightName );
            return new Redirect( '/Sphere/System/Authorization/Right', 0 );
        }
        return $View;
    }

    /**
     * @return Table
     */
    public function getTableAccess()
    {

        return parent::getTableAccess();
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
        if (false !== ( $Right = $this->entityAccessRightByRouteName( $Route ) )) {
            if (false !== ( $tblAccount = Gatekeeper::serviceAccount()->entityAccountBySession() )) {
                if (false !== ( $tblAccountRole = $tblAccount->getTblAccountRole() )) {
                    if (false !== ( $tblAccessList = Gatekeeper::serviceAccount()->entityAccessAllByAccountRole( $tblAccountRole ) )) {
                        /** @var TblAccess $tblAccess */
                        foreach ((array)$tblAccessList as $tblAccess) {
                            $tblPrivilegeList = $this->entityPrivilegeAllByAccess( $tblAccess );
                            foreach ((array)$tblPrivilegeList as $tblPrivilege) {
                                $tblRightList = $this->entityRightAllByPrivilege( $tblPrivilege );
                                /** @noinspection PhpUnusedParameterInspection */
                                array_walk( $tblRightList,
                                    function ( TblAccessRight &$tblRight, $I, TblAccessRight $Right ) {

                                        if ($tblRight->getId() == $Right->getId()) {
                                            // Access valid -> Access granted
                                            $tblRight = true;
                                        } else {
                                            // Right not valid -> Access denied
                                            $tblRight = false;
                                        }
                                    }, $Right );
                                $tblRightList = array_filter( $tblRightList );
                                if (!empty( $tblRightList )) {
                                    self::$AccessCache[] = $Route;
                                    return true;
                                }
                            }
                        }
                        // Access not valid -> Access denied
                        return false;
                    } else {
                        // Access-List invalid -> Access denied
                        return false;
                    }
                } else {
                    // Role invalid -> Access denied
                    return false;
                }
            } else {
                // Session invalid -> Access denied
                return false;
            }
        } else {
            if (!array_key_exists( 'REST', self::extensionRequest()->getParameterArray() )) {
                // Resource is not protected -> Access granted
                self::$AccessCache[] = $Route;
                return true;
            } else {
                // REST MUST BE protected -> Access denied
                return false;
            }
        }
    }

    /**
     * @param TblAccess $tblAccess
     *
     * @return bool|TblAccessPrivilege[]
     */
    public function entityPrivilegeAllByAccess( TblAccess $tblAccess )
    {

        return parent::entityPrivilegeAllByAccess( $tblAccess );
    }

    /**
     * @param TblAccessPrivilege $tblAccessPrivilege
     *
     * @return bool|TblAccessRight[]
     */
    public function entityRightAllByPrivilege( TblAccessPrivilege $tblAccessPrivilege )
    {

        return parent::entityRightAllByPrivilege( $tblAccessPrivilege );
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
     * @return bool|TblAccessRight[]
     */
    public function entityRightAll()
    {

        return parent::entityRightAll();
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
     * @param string $Name
     *
     * @return bool|TblAccess
     */
    public function entityAccessByName( $Name )
    {

        return parent::entityAccessByName( $Name );
    }

    /**
     * @return bool|TblAccess[]
     */
    public function entityAccessAll()
    {

        return parent::entityAccessAll();
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

    /**
     * @return bool|TblAccessPrivilege[]
     */
    public function entityPrivilegeAll()
    {

        return parent::entityPrivilegeAll();
    }

    /**
     * @param TblAccessPrivilege $TblAccessPrivilege
     * @param TblAccessRight     $TblAccessRight
     *
     * @return TblAccessRightList
     */
    public function executeAddPrivilegeRight(
        TblAccessPrivilege $TblAccessPrivilege,
        TblAccessRight $TblAccessRight
    ) {

        return parent::actionAddPrivilegeRight( $TblAccessPrivilege, $TblAccessRight );
    }

    /**
     * @param TblAccessPrivilege $TblAccessPrivilege
     * @param TblAccessRight     $TblAccessRight
     *
     * @return bool
     */
    public function executeRemovePrivilegeRight(
        TblAccessPrivilege $TblAccessPrivilege,
        TblAccessRight $TblAccessRight
    ) {

        return parent::actionRemovePrivilegeRight( $TblAccessPrivilege, $TblAccessRight );
    }

    /**
     * @param TblAccess          $tblAccess
     * @param TblAccessPrivilege $TblAccessPrivilege
     *
     * @return TblAccessPrivilegeList
     */
    public function executeAddAccessPrivilege(
        TblAccess $tblAccess,
        TblAccessPrivilege $TblAccessPrivilege
    ) {

        return parent::actionAddAccessPrivilege( $tblAccess, $TblAccessPrivilege );
    }

    /**
     * @param TblAccess          $tblAccess
     * @param TblAccessPrivilege $TblAccessPrivilege
     *
     * @return bool
     */
    public function executeRemoveAccessPrivilege(
        TblAccess $tblAccess,
        TblAccessPrivilege $TblAccessPrivilege
    ) {

        return parent::actionRemoveAccessPrivilege( $tblAccess, $TblAccessPrivilege );
    }
}
