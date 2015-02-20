<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRight;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Application\System\Frontend\Authorization\Summary\Summary;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageInfo;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
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
    public static function stageStatus()
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
            new TableData( Gatekeeper::serviceAccess()->entityRightAll(),
                new GridTableTitle( 'Überwachte Routen', 'Rechte' )
            )
            .new Summary( $TwigData )
        );
        return $View;
    }

    /**
     * @param null|string $Name
     *
     * @return Stage
     */
    public static function stageRight( $Name )
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
                , $Name )
        );
        return $View;
    }

    /**
     * @param null|string $Name
     *
     * @return Stage
     */
    public static function stagePrivilege( $Name )
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Privilegien' );

        $PrivilegeList = Gatekeeper::serviceAccess()->entityPrivilegeAll();
        array_walk( $PrivilegeList, function ( TblAccessPrivilege &$V, $I, $B ) {

            $V->Option =
                '<div class="pull-right">'
                .'<form action="'.$B.'/Sphere/System/Authorization/Privilege/Destroy" method="post">
                    <input type="hidden" class="form-control" name="Id" placeholder="" value="'.$V->getId().'"/>
                    <div class="form-group">
                        <div class="input-group">
                            <button type="submit" class="btn btn-danger">Löschen</button>
                        </div>
                    </div>
                </form>'
                .'</div>'
                .'<div class="pull-right">'
                .'<form action="'.$B.'/Sphere/System/Authorization/Privilege/Right" method="post">
                    <input type="hidden" class="form-control" name="Id" placeholder="" value="'.$V->getId().'"/>
                    <div class="form-group">
                        <div class="input-group">
                            <button type="submit" class="btn btn-primary">Rechte</button>
                        </div>
                    </div>&nbsp;
                </form>'
                .'</div>';
        }, self::getUrlBase() );

        $View->setContent(
            new TableData( $PrivilegeList, new GridTableTitle( 'Bestehende Privilegien', 'Rechtegruppen' ) )
            .Gatekeeper::serviceAccess()->executeCreatePrivilege(
                new FormDefault(
                    new GridFormGroup(
                        new GridFormRow(
                            new GridFormCol(
                                new InputText(
                                    'PrivilegeName', 'Name', 'Rechtegruppe'
                                )
                            )
                        ), new GridFormTitle( 'Privileg anlegen', 'Rechtegruppe' ) )
                    , new ButtonSubmitPrimary( 'Hinzufügen' )
                )
                , $Name )
        );
        return $View;
    }

    /**
     * @param null|string $Name
     *
     * @return Stage
     */
    public static function stageAccess( $Name )
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Zugriffslevel' );

        $AccessList = Gatekeeper::serviceAccess()->entityAccessAll();
        array_walk( $AccessList, function ( TblAccess &$V, $I, $B ) {

            $V->Option =
                '<div class="pull-right">'
                .'<form action="'.$B.'/Sphere/System/Authorization/Access/Destroy" method="post">
                    <input type="hidden" class="form-control" name="Id" placeholder="" value="'.$V->getId().'"/>
                    <div class="form-group">
                        <div class="input-group">
                            <button type="submit" class="btn btn-danger">Löschen</button>
                        </div>
                    </div>
                </form>'
                .'</div>'
                .'<div class="pull-right">'
                .'<form action="'.$B.'/Sphere/System/Authorization/Access/Privilege" method="post">
                    <input type="hidden" class="form-control" name="Id" placeholder="" value="'.$V->getId().'"/>
                    <div class="form-group">
                        <div class="input-group">
                            <button type="submit" class="btn btn-primary">Privilegien</button>
                        </div>
                    </div>&nbsp;
                </form>'
                .'</div>';
        }, self::getUrlBase() );

        $View->setContent(
            new TableData( $AccessList, new GridTableTitle( 'Bestehende Zugriffslevel', 'Privilegiengruppen' ) )
            .Gatekeeper::serviceAccess()->executeCreateAccess(
                new FormDefault(
                    new GridFormGroup(
                        new GridFormRow(
                            new GridFormCol(
                                new InputText(
                                    'AccessName', 'Name', 'Privilegiengruppe'
                                )
                            )
                        ), new GridFormTitle( 'Zugriffslevel anlegen', 'Privilegiengruppe' ) )
                    , new ButtonSubmitPrimary( 'Hinzufügen' )
                )
                , $Name )
        );
        return $View;
    }

    /**
     * @param null|string $Name
     *
     * @return Stage
     */
    public static function stageRole( $Name )
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
                                    'RoleName', 'Name', 'Zugriffslevelgruppe'
                                )
                            )
                        ), new GridFormTitle( 'Rolle anlegen', 'Zugriffslevelgruppe' ) )
                    , new ButtonSubmitPrimary( 'Hinzufügen' )
                )
                , $Name )
        );
        return $View;
    }

    /**
     * @param null|int $Id
     * @param null|int $Access
     *
     * @return Stage
     */
    public static function stageRoleAccess( $Id, $Access )
    {

        $tblAccountRole = Gatekeeper::serviceAccount()->entityAccountRoleById( $Id );
        $tblAccessList = Gatekeeper::serviceAccount()->entityAccessAllByAccountRole( $tblAccountRole );
        if (!$tblAccessList) {
            $tblAccessList = array();
        }

        $tblAccessListAvailable = array_udiff( Gatekeeper::serviceAccess()->entityAccessAll(), $tblAccessList,
            function ( TblAccess $ObjectA, TblAccess $ObjectB ) {

                return $ObjectA->getId() - $ObjectB->getId();
            }
        );

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Rolle - Zugriffslevel' );
        $View->setContent(
            new TableData( array( $tblAccountRole ), new GridTableTitle( 'Rolle' ), array(), false )
            .
            ( empty( $tblAccessList )
                ? new GridTableTitle( 'Rolle - Level' ).new MessageWarning( 'Keine Zugriffslevel vergeben' )
                : new TableData( $tblAccessList, new GridTableTitle( 'Rolle - Level' ), array(), true )
            )
            .
            ( empty( $tblAccessListAvailable )
                ? new GridTableTitle( 'Level' ).new MessageInfo( 'Keine weiteren Zugriffslevel verfügbar' )
                : new TableData( $tblAccessListAvailable, new GridTableTitle( 'Level' ), array(), true )
            )
        );
        return $View;
    }

    /**
     * @param null|int $Id
     * @param null|int $Privilege
     *
     * @return Stage
     */
    public static function stageAccessPrivilege( $Id, $Privilege )
    {

        $tblAccess = Gatekeeper::serviceAccess()->entityAccessById( $Id );
        $tblPrivilegeList = Gatekeeper::serviceAccess()->entityPrivilegeAllByAccess( $tblAccess );
        if (!$tblPrivilegeList) {
            $tblPrivilegeList = array();
        }

        $tblPrivilegeListAvailable = array_udiff( Gatekeeper::serviceAccess()->entityPrivilegeAll(), $tblPrivilegeList,
            function ( TblAccessPrivilege $ObjectA, TblAccessPrivilege $ObjectB ) {

                return $ObjectA->getId() - $ObjectB->getId();
            }
        );

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Level - Privilegien' );
        $View->setContent(
            new TableData( array( $tblAccess ), new GridTableTitle( 'Level' ), array(), false )
            .
            ( empty( $tblPrivilegeList )
                ? new GridTableTitle( 'Level - Privileg' ).new MessageWarning( 'Keine Privilegien vergeben' )
                : new TableData( $tblPrivilegeList, new GridTableTitle( 'Level - Privileg' ), array(), true )
            )
            .
            ( empty( $tblPrivilegeListAvailable )
                ? new GridTableTitle( 'Privilegien' ).new MessageInfo( 'Keine weiteren Privilegien verfügbar' )
                : new TableData( $tblPrivilegeListAvailable, new GridTableTitle( 'Privilegien' ), array(), true )
            )
        );
        return $View;
    }


    /**
     * @param null|int $Id
     * @param null|int $Right
     *
     * @return Stage
     */
    public static function stagePrivilegeRight( $Id, $Right )
    {

        $tblPrivilege = Gatekeeper::serviceAccess()->entityPrivilegeById( $Id );
        $tblRightList = Gatekeeper::serviceAccess()->entityRightAllByPrivilege( $tblPrivilege );

        $tblRightListAvailable = array_udiff( Gatekeeper::serviceAccess()->entityRightAll(), $tblRightList,
            function ( TblAccessRight $ObjectA, TblAccessRight $ObjectB ) {

                return $ObjectA->getId() - $ObjectB->getId();
            }
        );

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Privileg - Recht' );
        $View->setContent(
            new TableData( array( $tblPrivilege ), new GridTableTitle( 'Privileg' ), array(), false )
            .
            new TableData( $tblRightList, new GridTableTitle( 'Privileg - Recht' ), array(), false )
            .
            ( empty( $tblRightListAvailable )
                ? new GridTableTitle( 'Rechte' ).new MessageInfo( 'Keine weiteren Rechte verfügbar' )
                : new TableData( $tblRightListAvailable, new GridTableTitle( 'Rechte' ), array(), false )
            )
        );
        return $View;
    }
}
