<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Application\System\Frontend\Authorization\Access\Access;
use KREDA\Sphere\Application\System\Frontend\Authorization\Privilege\Privilege;
use KREDA\Sphere\Application\System\Frontend\Authorization\Right\Right;
use KREDA\Sphere\Application\System\Frontend\Authorization\Role\Role;
use KREDA\Sphere\Application\System\Frontend\Authorization\Summary\Summary;
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

        $TwigData = array();
        $tblAccountRoleList = Gatekeeper::serviceAccount()->entityAccountRoleAll();
        /** @var TblAccountRole $tblAccountRole */
        foreach ((array)$tblAccountRoleList as $tblAccountRole) {
            if (!$tblAccountRole) {
                continue;
            }
            $TwigData[$tblAccountRole->getId()] = array( 'Role' => $tblAccountRole );
            $tblAccessList = Gatekeeper::serviceAccount()->entityAccessAllByAccountRole( $tblAccountRole );
            /** @var TblAccess $tblAccess */
            foreach ((array)$tblAccessList as $tblAccess) {
                if (!$tblAccess) {
                    continue;
                }
                $TwigData[$tblAccountRole->getId()]['AccessList'][$tblAccess->getId()] = array( 'Access' => $tblAccess );
                $tblPrivilegeList = Gatekeeper::serviceAccess()->entityPrivilegeAllByAccess( $tblAccess );
                foreach ((array)$tblPrivilegeList as $tblPrivilege) {
                    if (!$tblPrivilege) {
                        continue;
                    }
                    $TwigData[$tblAccountRole->getId()]['AccessList'][$tblAccess->getId()]['PrivilegeList'][$tblPrivilege->getId()] = array( 'Privilege' => $tblPrivilege );
                    $tblRightList = Gatekeeper::serviceAccess()->entityRightAllByPrivilege( $tblPrivilege );
                    foreach ((array)$tblRightList as $tblRight) {
                        if (!$tblRight) {
                            continue;
                        }
                        if (!isset( $TwigData[$tblAccountRole->getId()]['AccessList'][$tblAccess->getId()]['PrivilegeList'][$tblPrivilege->getId()]['RightList'] )) {
                            $TwigData[$tblAccountRole->getId()]['AccessList'][$tblAccess->getId()]['PrivilegeList'][$tblPrivilege->getId()]['RightList'] = array();
                        }
                        $TwigData[$tblAccountRole->getId()]['AccessList'][$tblAccess->getId()]['PrivilegeList'][$tblPrivilege->getId()]['RightList'][] = $tblRight;
                    }
                }
            }

        }
        $View->setContent( new Summary( $TwigData, Gatekeeper::serviceAccess()->entityRightAll() ) );
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
