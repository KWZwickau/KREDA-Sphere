<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageInfo;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonLinkPrimary;
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
use KREDA\Sphere\Common\Frontend\Redirect;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

/**
 * Class Role
 *
 * @package KREDA\Sphere\Application\System\Frontend\Authorization
 */
class Role extends Access
{

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

            $LinkList = Gatekeeper::serviceAccount()->entityAccessAllByAccountRole( $V );
            if (empty( $LinkList )) {
                $V->Available = new MessageWarning( 'Keine Zugriffslevel vergeben' );
            } else {
                $V->Available = new TableData( $LinkList, null, array( 'Name' => 'Zugriffslevel' ), false );
            }

            $V->Option = new ButtonLinkPrimary( 'Zugriffslevel bearbeiten', '/Sphere/System/Authorization/Role/Access',
                null, array( 'Id' => $V->getId() ) );
        }, self::getUrlBase() );

        $View->setContent(
            new TableData( $RoleList, new GridTableTitle( 'Bestehende Rollen', 'Zugriffslevelgruppen' ),
                array( 'Name' => 'Rolle', 'Available' => 'Zugriffslevel', 'Option' => 'Optionen' )
            )
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
                $View->setContent( new Redirect( '/Sphere/System/Authorization/Role/Access', 0,
                    array( 'Id' => $Id ) ) );
                return $View;
            } else {
                Gatekeeper::serviceAccount()->executeAddRoleAccess( $tblRole, $tblAccess );
                $View->setContent( new Redirect( '/Sphere/System/Authorization/Role/Access', 0,
                    array( 'Id' => $Id ) ) );
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

}
