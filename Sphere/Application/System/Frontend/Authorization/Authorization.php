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
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitDanger;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitSuccess;
use KREDA\Sphere\Common\Frontend\Form\Element\InputHidden;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayout;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutCol;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutGroup;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutRow;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutTitle;
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
     * @param bool     $Remove
     *
     * @return Stage
     */
    public static function stageRoleAccess( $Id, $Access, $Remove = false )
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Rolle - Zugriffslevel' );

        $tblRole = Gatekeeper::serviceAccount()->entityAccountRoleById( $Id );
        if ($tblRole && null !== $Access && ( $tblAccess = Gatekeeper::serviceAccess()->entityAccessById( $Access ) )) {
            if ($Remove) {
                Gatekeeper::serviceAccount()->executeRemoveRoleAccess( $tblRole, $tblAccess );
                $View->setContent( self::getRedirect( '/Sphere/System/Authorization/Role/Access?Id='.$Id, 0 ) );
                return $View;
            } else {
                Gatekeeper::serviceAccount()->executeAddRoleAccess( $tblRole, $tblAccess );
                $View->setContent( self::getRedirect( '/Sphere/System/Authorization/Role/Access?Id='.$Id, 0 ) );
                return $View;
            }
        }
        $tblAccessList = Gatekeeper::serviceAccount()->entityAccessAllByAccountRole( $tblRole );
        if (!$tblAccessList) {
            $tblAccessList = array();
        }

        $tblAccessListAvailable = array_udiff( Gatekeeper::serviceAccess()->entityAccessAll(), $tblAccessList,
            function ( TblAccess $ObjectA, TblAccess $ObjectB ) {

                return $ObjectA->getId() - $ObjectB->getId();
            }
        );

        array_walk( $tblAccessListAvailable, function ( TblAccess &$V, $I, $B ) {

            $Id = new InputHidden( 'Id' );
            $Id->setDefaultValue( $B[0], true );
            $Access = new InputHidden( 'Access' );
            $Access->setDefaultValue( $V->getId(), true );

            $V->Option =
                '<div class="pull-right">'
                .new FormDefault( new GridFormGroup( new GridFormRow( new GridFormCol( array(
                    $Id,
                    $Access,
                    new ButtonSubmitSuccess( 'Hinzufügen' )
                ) ) ) ),
                    null, $B[1].'/Sphere/System/Authorization/Role/Access'
                )
                .'</div>';
        }, array( $Id, self::getUrlBase() ) );

        array_walk( $tblAccessList, function ( TblAccess &$V, $I, $B ) {

            $Id = new InputHidden( 'Id' );
            $Id->setDefaultValue( $B[0], true );
            $Access = new InputHidden( 'Access' );
            $Access->setDefaultValue( $V->getId(), true );
            $Remove = new InputHidden( 'Remove' );
            $Remove->setDefaultValue( 1, true );

            $V->Option =
                '<div class="pull-right">'
                .new FormDefault( new GridFormGroup( new GridFormRow( new GridFormCol( array(
                    $Id,
                    $Access,
                    $Remove,
                    new ButtonSubmitDanger( 'Entfernen' )
                ) ) ) ),
                    null, $B[1].'/Sphere/System/Authorization/Role/Access'
                )
                .'</div>';
        }, array( $Id, self::getUrlBase() ) );

        $View->setContent(
            new TableData( array( $tblRole ), new GridTableTitle( 'Rolle' ), array(), false )
            .
            new GridLayout(
                new GridLayoutGroup(
                    new GridLayoutRow( array(
                        new GridLayoutCol( array(
                            new GridLayoutTitle( 'Zugriffslevel', 'Zugewiesen' ),
                            ( empty( $tblAccessList )
                                ? new MessageWarning( 'Keine Zugriffslevel vergeben' )
                                : new TableData( $tblAccessList )
                            )
                        ), 6 ),
                        new GridLayoutCol( array(
                            new GridLayoutTitle( 'Zugriffslevel', 'Verfügbar' ),
                            ( empty( $tblAccessListAvailable )
                                ? new MessageInfo( 'Keine weiteren Zugriffslevel verfügbar' )
                                : new TableData( $tblAccessListAvailable )
                            )
                        ), 6 )
                    ) )
                    , new GridLayoutTitle( 'Rollen', 'Zusammensetzung' ) )
            )
        );
        return $View;
    }

    /**
     * @param null|int $Id
     * @param null|int $Privilege
     * @param bool     $Remove
     *
     * @return Stage
     */
    public static function stageAccessPrivilege( $Id, $Privilege, $Remove = false )
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Zugriffslevel - Privilegien' );

        $tblAccess = Gatekeeper::serviceAccess()->entityAccessById( $Id );
        if ($tblAccess && null !== $Privilege && ( $tblPrivilege = Gatekeeper::serviceAccess()->entityPrivilegeById( $Privilege ) )) {
            if ($Remove) {
                Gatekeeper::serviceAccess()->executeRemoveAccessPrivilege( $tblAccess, $tblPrivilege );
                $View->setContent( self::getRedirect( '/Sphere/System/Authorization/Access/Privilege?Id='.$Id, 0 ) );
                return $View;
            } else {
                Gatekeeper::serviceAccess()->executeAddAccessPrivilege( $tblAccess, $tblPrivilege );
                $View->setContent( self::getRedirect( '/Sphere/System/Authorization/Access/Privilege?Id='.$Id, 0 ) );
                return $View;
            }
        }
        $tblPrivilegeList = Gatekeeper::serviceAccess()->entityPrivilegeAllByAccess( $tblAccess );
        if (!$tblPrivilegeList) {
            $tblPrivilegeList = array();
        }

        $tblPrivilegeListAvailable = array_udiff( Gatekeeper::serviceAccess()->entityPrivilegeAll(), $tblPrivilegeList,
            function ( TblAccessPrivilege $ObjectA, TblAccessPrivilege $ObjectB ) {

                return $ObjectA->getId() - $ObjectB->getId();
            }
        );

        array_walk( $tblPrivilegeListAvailable, function ( TblAccessPrivilege &$V, $I, $B ) {

            $Id = new InputHidden( 'Id' );
            $Id->setDefaultValue( $B[0], true );
            $Privilege = new InputHidden( 'Privilege' );
            $Privilege->setDefaultValue( $V->getId(), true );

            $V->Option =
                '<div class="pull-right">'
                .new FormDefault( new GridFormGroup( new GridFormRow( new GridFormCol( array(
                    $Id,
                    $Privilege,
                    new ButtonSubmitSuccess( 'Hinzufügen' )
                ) ) ) ),
                    null, $B[1].'/Sphere/System/Authorization/Access/Privilege'
                )
                .'</div>';
        }, array( $Id, self::getUrlBase() ) );

        array_walk( $tblPrivilegeList, function ( TblAccessPrivilege &$V, $I, $B ) {

            $Id = new InputHidden( 'Id' );
            $Id->setDefaultValue( $B[0], true );
            $Privilege = new InputHidden( 'Privilege' );
            $Privilege->setDefaultValue( $V->getId(), true );
            $Remove = new InputHidden( 'Remove' );
            $Remove->setDefaultValue( 1, true );

            $V->Option =
                '<div class="pull-right">'
                .new FormDefault( new GridFormGroup( new GridFormRow( new GridFormCol( array(
                    $Id,
                    $Privilege,
                    $Remove,
                    new ButtonSubmitDanger( 'Entfernen' )
                ) ) ) ),
                    null, $B[1].'/Sphere/System/Authorization/Access/Privilege'
                )
                .'</div>';
        }, array( $Id, self::getUrlBase() ) );

        $View->setContent(
            new TableData( array( $tblAccess ), new GridTableTitle( 'Zugriffslevel' ), array(), false )
            .
            new GridLayout(
                new GridLayoutGroup(
                    new GridLayoutRow( array(
                        new GridLayoutCol( array(
                            new GridLayoutTitle( 'Privilegien', 'Zugewiesen' ),
                            ( empty( $tblPrivilegeList )
                                ? new MessageWarning( 'Keine Privilegien vergeben' )
                                : new TableData( $tblPrivilegeList )
                            )
                        ), 6 ),
                        new GridLayoutCol( array(
                            new GridLayoutTitle( 'Privilegien', 'Verfügbar' ),
                            ( empty( $tblPrivilegeListAvailable )
                                ? new MessageInfo( 'Keine weiteren Privilegien verfügbar' )
                                : new TableData( $tblPrivilegeListAvailable )
                            )
                        ), 6 )
                    ) )
                    , new GridLayoutTitle( 'Zugriffslevel', 'Zusammensetzung' ) )
            )
        );
        return $View;
    }


    /**
     * @param null|int $Id
     * @param null|int $Right
     * @param bool     $Remove
     *
     * @return Stage
     */
    public static function stagePrivilegeRight( $Id, $Right, $Remove = false )
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Privileg - Rechte' );

        $tblPrivilege = Gatekeeper::serviceAccess()->entityPrivilegeById( $Id );
        if ($tblPrivilege && null !== $Right && ( $tblRight = Gatekeeper::serviceAccess()->entityRightById( $Right ) )) {
            if ($Remove) {
                Gatekeeper::serviceAccess()->executeRemovePrivilegeRight( $tblPrivilege, $tblRight );
                $View->setContent( self::getRedirect( '/Sphere/System/Authorization/Privilege/Right?Id='.$Id, 0 ) );
                return $View;
            } else {
                Gatekeeper::serviceAccess()->executeAddPrivilegeRight( $tblPrivilege, $tblRight );
                $View->setContent( self::getRedirect( '/Sphere/System/Authorization/Privilege/Right?Id='.$Id, 0 ) );
                return $View;
            }
        }
        $tblRightList = Gatekeeper::serviceAccess()->entityRightAllByPrivilege( $tblPrivilege );

        $tblRightListAvailable = array_udiff( Gatekeeper::serviceAccess()->entityRightAll(), $tblRightList,
            function ( TblAccessRight $ObjectA, TblAccessRight $ObjectB ) {

                return $ObjectA->getId() - $ObjectB->getId();
            }
        );

        array_walk( $tblRightListAvailable, function ( TblAccessRight &$V, $I, $B ) {

            $Id = new InputHidden( 'Id' );
            $Id->setDefaultValue( $B[0], true );
            $Right = new InputHidden( 'Right' );
            $Right->setDefaultValue( $V->getId(), true );

            $V->Option =
                '<div class="pull-right">'
                .new FormDefault( new GridFormGroup( new GridFormRow( new GridFormCol( array(
                    $Id,
                    $Right,
                    new ButtonSubmitSuccess( 'Hinzufügen' )
                ) ) ) ),
                    null, $B[1].'/Sphere/System/Authorization/Privilege/Right'
                )
                .'</div>';
        }, array( $Id, self::getUrlBase() ) );

        array_walk( $tblRightList, function ( TblAccessRight &$V, $I, $B ) {

            $Id = new InputHidden( 'Id' );
            $Id->setDefaultValue( $B[0], true );
            $Right = new InputHidden( 'Right' );
            $Right->setDefaultValue( $V->getId(), true );
            $Remove = new InputHidden( 'Remove' );
            $Remove->setDefaultValue( 1, true );

            $V->Option =
                '<div class="pull-right">'
                .$F = new FormDefault( new GridFormGroup( new GridFormRow( new GridFormCol( array(
                        $Id,
                        $Right,
                        $Remove,
                        new ButtonSubmitDanger( 'Entfernen' )
                    ) ) ) ),
                        null, $B[1].'/Sphere/System/Authorization/Privilege/Right'
                    )
                    .'</div>';
        }, array( $Id, self::getUrlBase() ) );

        $View->setContent(
            new TableData( array( $tblPrivilege ), new GridTableTitle( 'Privileg' ), array(), false )
            .
            new GridLayout(
                new GridLayoutGroup(
                    new GridLayoutRow( array(
                        new GridLayoutCol( array(
                            new GridLayoutTitle( 'Rechte', 'Zugewiesen' ),
                            ( empty( $tblRightList )
                                ? new MessageWarning( 'Keine Rechte vergeben' )
                                : new TableData( $tblRightList )
                            )
                        ), 6 ),
                        new GridLayoutCol( array(
                            new GridLayoutTitle( 'Rechte', 'Verfügbar' ),
                            ( empty( $tblRightListAvailable )
                                ? new MessageInfo( 'Keine weiteren Rechte verfügbar' )
                                : new TableData( $tblRightListAvailable )
                            )
                        ), 6 )
                    ) )
                    , new GridLayoutTitle( 'Privileg', 'Zusammensetzung' ) )
            )
        );
        return $View;
    }
}
