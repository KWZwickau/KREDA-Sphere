<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitDanger;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitSuccess;
use KREDA\Sphere\Client\Frontend\Layout\Type\Column;
use KREDA\Sphere\Client\Frontend\Layout\Type\Grid;
use KREDA\Sphere\Client\Frontend\Layout\Type\Group;
use KREDA\Sphere\Client\Frontend\Layout\Type\PullRight;
use KREDA\Sphere\Client\Frontend\Layout\Type\Row;
use KREDA\Sphere\Client\Frontend\Layout\Type\Title;
use KREDA\Sphere\Client\Frontend\Message\Type\Info;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Frontend\Form\Element\InputHidden;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

/**
 * Class Access
 *
 * @package KREDA\Sphere\Application\System\Frontend\Authorization
 */
class Access extends Privilege
{

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
        array_walk( $AccessList, function ( TblAccess &$V ) {

            $Id = new InputHidden( 'Id' );
            $Id->setDefaultValue( $V->getId(), true );

            $LinkList = Gatekeeper::serviceAccess()->entityPrivilegeAllByAccess( $V );
            if (empty( $LinkList )) {
                /** @noinspection PhpUndefinedFieldInspection */
                $V->Available = new Warning( 'Keine Privilegien vergeben' );
            } else {
                /** @noinspection PhpUndefinedFieldInspection */
                $V->Available = new TableData( $LinkList, null, array( 'Name' => 'Privilegien' ), false );
            }

            /** @noinspection PhpUndefinedFieldInspection */
            $V->Option =
                ( new FormDefault( new GridFormGroup( new GridFormRow( new GridFormCol( array(
                    $Id,
                    new SubmitPrimary( 'Privilegien bearbeiten' )
                ) ) ) ), null, '/Sphere/System/Authorization/Access/Privilege' ) )->__toString();

        } );

        $View->setContent(
            new TableData( $AccessList, new GridTableTitle( 'Bestehende Zugriffslevel', 'Privilegiengruppen' ),
                array( 'Name' => 'Zugriffslevel', 'Available' => 'Privileg', 'Option' => 'Optionen' )
            )
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
                    , new SubmitPrimary( 'Hinzufügen' )
                )
                , $Name )
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
                $View->setContent( new Redirect( '/Sphere/System/Authorization/Access/Privilege', 0,
                    array( 'Id' => $Id ) ) );
                return $View;
            } else {
                Gatekeeper::serviceAccess()->executeAddAccessPrivilege( $tblAccess, $tblPrivilege );
                $View->setContent( new Redirect( '/Sphere/System/Authorization/Access/Privilege', 0,
                    array( 'Id' => $Id ) ) );
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

        /** @noinspection PhpUnusedParameterInspection */
        array_walk( $tblPrivilegeListAvailable, function ( TblAccessPrivilege &$Entity, $Index, $Identifier ) {

            $Id = new InputHidden( 'Id' );
            $Id->setDefaultValue( $Identifier, true );
            $Privilege = new InputHidden( 'Privilege' );
            $Privilege->setDefaultValue( $Entity->getId(), true );

            /** @noinspection PhpUndefinedFieldInspection */
            $Entity->Option = ( new PullRight(
                new FormDefault( new GridFormGroup( new GridFormRow( new GridFormCol( array(
                    $Id,
                    $Privilege,
                    new SubmitSuccess( 'Hinzufügen' )
                ) ) ) ),
                    null, '/Sphere/System/Authorization/Access/Privilege'
                )
            ) )->__toString();
        }, $Id );

        /** @noinspection PhpUnusedParameterInspection */
        array_walk( $tblPrivilegeList, function ( TblAccessPrivilege &$Entity, $Index, $Identifier ) {

            $Id = new InputHidden( 'Id' );
            $Id->setDefaultValue( $Identifier, true );
            $Privilege = new InputHidden( 'Privilege' );
            $Privilege->setDefaultValue( $Entity->getId(), true );
            $Remove = new InputHidden( 'Remove' );
            $Remove->setDefaultValue( 1, true );

            /** @noinspection PhpUndefinedFieldInspection */
            $Entity->Option = ( new PullRight(
                new FormDefault( new GridFormGroup( new GridFormRow( new GridFormCol( array(
                    $Id,
                    $Privilege,
                    $Remove,
                    new SubmitDanger( 'Entfernen' )
                ) ) ) ),
                    null, '/Sphere/System/Authorization/Access/Privilege'
                )
            ) )->__toString();
        }, $Id );

        $View->setContent(
            new TableData( array( $tblAccess ), new GridTableTitle( 'Zugriffslevel' ), array(), false )
            .
            new Grid(
                new Group(
                    new Row( array(
                        new Column( array(
                            new Title( 'Privilegien', 'Zugewiesen' ),
                            ( empty( $tblPrivilegeList )
                                ? new Warning( 'Keine Privilegien vergeben' )
                                : new TableData( $tblPrivilegeList )
                            )
                        ), 6 ),
                        new Column( array(
                            new Title( 'Privilegien', 'Verfügbar' ),
                            ( empty( $tblPrivilegeListAvailable )
                                ? new Info( 'Keine weiteren Privilegien verfügbar' )
                                : new TableData( $tblPrivilegeListAvailable )
                            )
                        ), 6 )
                    ) )
                    , new Title( 'Zugriffslevel', 'Zusammensetzung' ) )
            )
        );
        return $View;
    }
}
