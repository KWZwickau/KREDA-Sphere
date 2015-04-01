<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitDanger;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitSuccess;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn as FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup as FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow as FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle as FormTitle;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\HiddenField;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutRight;
use KREDA\Sphere\Client\Frontend\Message\Type\Info;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableTitle;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;

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
        array_walk( $RoleList, function ( TblAccountRole &$V ) {

            $LinkList = Gatekeeper::serviceAccount()->entityAccessAllByAccountRole( $V );
            if (empty( $LinkList )) {
                /** @noinspection PhpUndefinedFieldInspection */
                $V->Available = new Warning( 'Keine Zugriffslevel vergeben' );
            } else {
                /** @noinspection PhpUndefinedFieldInspection */
                $V->Available = new TableData( $LinkList, null, array( 'Name' => 'Zugriffslevel' ), false );
            }

            /** @noinspection PhpUndefinedFieldInspection */
            $V->Option = ( new Primary( 'Zugriffslevel bearbeiten',
                '/Sphere/System/Authorization/Role/Access',
                null, array( 'Id' => $V->getId() ) ) )->__toString();
        } );

        $View->setContent(
            new TableData( $RoleList, new TableTitle( 'Bestehende Rollen', 'Zugriffslevelgruppen' ),
                array( 'Name' => 'Rolle', 'Available' => 'Zugriffslevel', 'Option' => 'Optionen' )
            )
            .Gatekeeper::serviceAccount()->executeCreateRole(
                new Form(
                    new FormGroup(
                        new FormRow(
                            new FormColumn(
                                new TextField(
                                    'RoleName', 'Name', 'Zugriffslevelgruppe'
                                )
                            )
                        ), new FormTitle( 'Rolle anlegen', 'Zugriffslevelgruppe' ) )
                    , new SubmitPrimary( 'Hinzufügen' )
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

        /** @noinspection PhpUnusedParameterInspection */
        array_walk( $tblAccessListAvailable, function ( TblAccess &$Entity, $Index, $Identifier ) {

            $Id = new HiddenField( 'Id' );
            $Id->setDefaultValue( $Identifier, true );
            $Access = new HiddenField( 'Access' );
            $Access->setDefaultValue( $Entity->getId(), true );

            /** @noinspection PhpUndefinedFieldInspection */
            $Entity->Option = ( new LayoutRight(
                new Form( new FormGroup( new FormRow( new FormColumn( array(
                    $Id,
                    $Access,
                    new SubmitSuccess( 'Hinzufügen' )
                ) ) ) ),
                    null, '/Sphere/System/Authorization/Role/Access'
                )
            ) )->__toString();
        }, $Id );

        /** @noinspection PhpUnusedParameterInspection */
        array_walk( $tblAccessList, function ( TblAccess &$Entity, $Index, $Identifier ) {

            $Id = new HiddenField( 'Id' );
            $Id->setDefaultValue( $Identifier, true );
            $Access = new HiddenField( 'Access' );
            $Access->setDefaultValue( $Entity->getId(), true );
            $Remove = new HiddenField( 'Remove' );
            $Remove->setDefaultValue( 1, true );

            /** @noinspection PhpUndefinedFieldInspection */
            $Entity->Option = ( new LayoutRight(
                new Form( new FormGroup( new FormRow( new FormColumn( array(
                    $Id,
                    $Access,
                    $Remove,
                    new SubmitDanger( 'Entfernen' )
                ) ) ) ),
                    null, '/Sphere/System/Authorization/Role/Access'
                )
            ) )->__toString();
        }, $Id );

        $View->setContent(
            new TableData( array( $tblRole ), new TableTitle( 'Rolle' ), array(), false )
            .
            new Layout(
                new LayoutGroup(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutTitle( 'Zugriffslevel', 'Zugewiesen' ),
                            ( empty( $tblAccessList )
                                ? new Warning( 'Keine Zugriffslevel vergeben' )
                                : new TableData( $tblAccessList )
                            )
                        ), 6 ),
                        new LayoutColumn( array(
                            new LayoutTitle( 'Zugriffslevel', 'Verfügbar' ),
                            ( empty( $tblAccessListAvailable )
                                ? new Info( 'Keine weiteren Zugriffslevel verfügbar' )
                                : new TableData( $tblAccessListAvailable )
                            )
                        ), 6 )
                    ) )
                    , new LayoutTitle( 'Rollen', 'Zusammensetzung' ) )
            )
        );
        return $View;
    }

}
