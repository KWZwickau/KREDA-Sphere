<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRight;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitDanger;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitSuccess;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle;
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
 * Class Privilege
 *
 * @package KREDA\Sphere\Application\System\Frontend\Authorization
 */
class Privilege extends Right
{

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

        array_walk( $PrivilegeList, function ( TblAccessPrivilege &$V ) {

            $Id = new HiddenField( 'Id' );
            $Id->setDefaultValue( $V->getId(), true );

            $LinkList = Gatekeeper::serviceAccess()->entityRightAllByPrivilege( $V );
            if (empty( $LinkList )) {
                /** @noinspection PhpUndefinedFieldInspection */
                $V->Available = new Warning( 'Keine Rechte vergeben' );
            } else {
                /** @noinspection PhpUndefinedFieldInspection */
                $V->Available = new TableData( $LinkList, null, array( 'Route' => 'Recht' ), false );
            }

            /** @noinspection PhpUndefinedFieldInspection */
            $V->Option = ( new Form( new FormGroup( new FormRow( new FormColumn(
                array(
                    $Id,
                    new SubmitPrimary( 'Rechte bearbeiten' )
                )
            ) ) ), null, '/Sphere/System/Authorization/Privilege/Right' ) )->__toString();

        } );

        $View->setContent(
            new TableData( $PrivilegeList, new TableTitle( 'Bestehende Privilegien', 'Rechtegruppen' ),
                array( 'Name' => 'Privileg', 'Available' => 'Rechte', 'Option' => 'Optionen' )
            )
            .Gatekeeper::serviceAccess()->executeCreatePrivilege(
                new Form(
                    new FormGroup(
                        new FormRow(
                            new FormColumn(
                                new TextField(
                                    'PrivilegeName', 'Name', 'Rechtegruppe'
                                )
                            )
                        ), new FormTitle( 'Privileg anlegen', 'Rechtegruppe' ) )
                    , new SubmitPrimary( 'Hinzufügen' )
                )
                , $Name )
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
                $View->setContent( new Redirect( '/Sphere/System/Authorization/Privilege/Right', 0,
                    array( 'Id' => $Id ) ) );
                return $View;
            } else {
                Gatekeeper::serviceAccess()->executeAddPrivilegeRight( $tblPrivilege, $tblRight );
                $View->setContent( new Redirect( '/Sphere/System/Authorization/Privilege/Right', 0,
                    array( 'Id' => $Id ) ) );
                return $View;
            }
        }
        $tblRightList = Gatekeeper::serviceAccess()->entityRightAllByPrivilege( $tblPrivilege );

        $tblRightListAvailable = array_udiff( Gatekeeper::serviceAccess()->entityRightAll(), $tblRightList,
            function ( TblAccessRight $ObjectA, TblAccessRight $ObjectB ) {

                return $ObjectA->getId() - $ObjectB->getId();
            }
        );

        /** @noinspection PhpUnusedParameterInspection */
        array_walk( $tblRightListAvailable, function ( TblAccessRight &$Entity, $Index, $Identifier ) {

            $Id = new HiddenField( 'Id' );
            $Id->setDefaultValue( $Identifier, true );
            $Right = new HiddenField( 'Right' );
            $Right->setDefaultValue( $Entity->getId(), true );

            /** @noinspection PhpUndefinedFieldInspection */
            $Entity->Option = ( new LayoutRight(
                new Form( new FormGroup( new FormRow( new FormColumn( array(
                    $Id,
                    $Right,
                    new SubmitSuccess( 'Hinzufügen' )
                ) ) ) ),
                    null, '/Sphere/System/Authorization/Privilege/Right'
                )
            ) )->__toString();
        }, $Id );

        /** @noinspection PhpUnusedParameterInspection */
        array_walk( $tblRightList, function ( TblAccessRight &$Entity, $Index, $Identifier ) {

            $Id = new HiddenField( 'Id' );
            $Id->setDefaultValue( $Identifier, true );
            $Right = new HiddenField( 'Right' );
            $Right->setDefaultValue( $Entity->getId(), true );
            $Remove = new HiddenField( 'Remove' );
            $Remove->setDefaultValue( 1, true );

            /** @noinspection PhpUndefinedFieldInspection */
            $Entity->Option = ( new LayoutRight(
                new Form( new FormGroup( new FormRow( new FormColumn(
                    array(
                        $Id,
                        $Right,
                        $Remove,
                        new SubmitDanger( 'Entfernen' )
                    )
                ) ) ), null, '/Sphere/System/Authorization/Privilege/Right' )
            ) )->__toString();
        }, $Id );

        $View->setContent(
            new TableData( array( $tblPrivilege ), new TableTitle( 'Privileg' ), array(), false )
            .
            new Layout(
                new LayoutGroup(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutTitle( 'Rechte', 'Zugewiesen' ),
                            ( empty( $tblRightList )
                                ? new Warning( 'Keine Rechte vergeben' )
                                : new TableData( $tblRightList )
                            )
                        ), 6 ),
                        new LayoutColumn( array(
                            new LayoutTitle( 'Rechte', 'Verfügbar' ),
                            ( empty( $tblRightListAvailable )
                                ? new Info( 'Keine weiteren Rechte verfügbar' )
                                : new TableData( $tblRightListAvailable )
                            )
                        ), 6 )
                    ) )
                    , new LayoutTitle( 'Privileg', 'Zusammensetzung' ) )
            )
        );
        return $View;
    }
}
