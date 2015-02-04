<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Application\System\Frontend\Authorization\Summary\Summary;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

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
                /** @var TblAccessPrivilege $tblPrivilege */
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
        $View->setContent(
            new Summary( $TwigData )
            .new TableData( Gatekeeper::serviceAccess()->entityRightAll(),
                new GridTableTitle( 'Überwachte Rechte', 'Routen' ) )
        );
        return $View;
    }

    /**
     * @param null|string $Name
     *
     * @return Stage
     */
    public static function stageAuthorizationRight( $Name )
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Rechte' );
        $View->setContent(
            new TableData( Gatekeeper::serviceAccess()->entityRightAll(),
                new GridTableTitle( 'Bestehende Rechte', 'Routen' ) )
            .Gatekeeper::serviceAccess()->executeCreateRight(
                new FormDefault(
                    new GridFormGroup(
                        new GridFormRow(
                            new GridFormCol(
                                new InputText(
                                    'RightName', 'Route', 'Name'
                                )
                            )
                        ), new GridFormTitle( 'Recht anlegen', 'Route' ) )
                , new ButtonSubmitPrimary( 'Hinzufügen' )
                )
            ,$Name)
        );
        return $View;
    }

    /**
     * @param null|string $Name
     *
     * @return Stage
     */
    public static function stageAuthorizationPrivilege( $Name )
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Privilegien' );
        $View->setContent(
            new TableData( Gatekeeper::serviceAccess()->entityPrivilegeAll(),
                new GridTableTitle( 'Bestehende Privilegien', 'Rechtegruppen' ) )
            .Gatekeeper::serviceAccess()->executeCreatePrivilege(
                new FormDefault(
                    new GridFormGroup(
                        new GridFormRow(
                            new GridFormCol(
                                new InputText(
                                    'PrivilegeName', 'Name', 'Rechtegruppe'
                                )
                            )
                        ), new GridFormTitle( 'Privileg anlegen', 'Rechtegruppe'))
                , new ButtonSubmitPrimary( 'Hinzufügen' )
                )
            , $Name)
        );
        return $View;
    }

    /**
     * @param null|string $Name
     *
     * @return Stage
     */
    public static function stageAuthorizationAccess( $Name )
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Zugriffslevel' );
        $View->setContent(
            new TableData( Gatekeeper::serviceAccess()->entityAccessAll(),
                new GridTableTitle( 'Bestehende Zugriffslevel', 'Privilegiengruppen' ) )
            .Gatekeeper::serviceAccess()->executeCreateAccess(
                new FormDefault(
                    new GridFormGroup(
                        new GridFormRow(
                            new GridFormCol(
                                new InputText(
                                    'AccessName', 'Name', 'Privilegiengruppe'
                                )
                            )
                        ), new GridFormTitle( 'Zugriffslevel anlegen', 'Privilegiengruppe'))
                , new ButtonSubmitPrimary( 'Hinzufügen' )
                )
            , $Name)
        );
        return $View;
    }

    /**
     * @param null|string $Name
     *
     * @return Stage
     */
    public static function stageAuthorizationRole( $Name )
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Rollen' );

        $RoleList = Gatekeeper::serviceAccount()->entityAccountRoleAll();
        array_walk( $RoleList, function ( TblAccountRole &$V, $I, $B ) {

            $V->Option =
                '<div class="pull-right">'
                .'<form action="'.$B.'/Sphere/System/Authorization/Role/Destroy" method="post">
                    <input type="hidden" class="form-control" name="Id" placeholder="" value="'.$V->getId().'"/>
                    <div class="form-group">
                        <div class="input-group">
                            <button type="submit" class="btn btn-danger">Löschen</button>
                        </div>
                    </div>
                </form>'
                .'</div>'
                .'<div class="pull-right">'
                .'<form action="'.$B.'/Sphere/System/Authorization/Role/Access" method="post">
                    <input type="hidden" class="form-control" name="Id" placeholder="" value="'.$V->getId().'"/>
                    <div class="form-group">
                        <div class="input-group">
                            <button type="submit" class="btn btn-primary">Zugriffslevel</button>
                        </div>
                    </div>&nbsp;
                </form>'
                .'</div>';
        }, self::getUrlBase() );

        $View->setContent(
            new TableData( $RoleList, new GridTableTitle( 'Bestehende Rollen', 'Zugriffslevelgruppen' ) )
            .Gatekeeper::serviceAccount()->executeCreateRole(
                new FormDefault(
                    new GridFormGroup(
                        new GridFormRow(
                            new GridFormCol(
                                new InputText(
                                    'Access', 'Name', 'Zugriffslevelgruppe'
                                )
                            )
                        ), new GridFormTitle( 'Rolle anlegen', 'Zugriffslevelgruppe') )
                , new ButtonSubmitPrimary( 'Hinzufügen' )
                )
            , $Name)
        );
        return $View;
    }

    /**
     * @param null|int $Role
     * @param null|int $Access
     *
     * @return Stage
     */
    public static function stageAuthorizationRoleAccess( $Role, $Access )
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Rollen' );
        $View->setContent(
            new TableData( Gatekeeper::serviceAccount()->entityAccountRoleAll(),
                new GridTableTitle( 'Bestehende Rollen', 'Zugriffslevelgruppen' ) )
        );
        return $View;
    }

}
