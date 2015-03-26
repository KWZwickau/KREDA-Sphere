<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRight;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
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
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutRight;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutRow;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutTitle;
use KREDA\Sphere\Common\Frontend\Redirect;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

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
        /** @noinspection PhpUnusedParameterInspection */
        array_walk( $PrivilegeList, function ( TblAccessPrivilege &$V, $I, $B ) {

            $Id = new InputHidden( 'Id' );
            $Id->setDefaultValue( $V->getId(), true );

            $LinkList = Gatekeeper::serviceAccess()->entityRightAllByPrivilege( $V );
            if (empty( $LinkList )) {
                /** @noinspection PhpUndefinedFieldInspection */
                $V->Available = new MessageWarning( 'Keine Rechte vergeben' );
            } else {
                /** @noinspection PhpUndefinedFieldInspection */
                $V->Available = new TableData( $LinkList, null, array( 'Route' => 'Recht' ), false );
            }

            /** @noinspection PhpUndefinedFieldInspection */
            $V->Option = ''
                .new FormDefault( new GridFormGroup( new GridFormRow( new GridFormCol( array(
                    $Id,
                    new ButtonSubmitPrimary( 'Rechte bearbeiten' )
                ) ) ) ), null, $B.'/Sphere/System/Authorization/Privilege/Right' );

        }, self::getUrlBase() );

        $View->setContent(
            new TableData( $PrivilegeList, new GridTableTitle( 'Bestehende Privilegien', 'Rechtegruppen' ),
                array( 'Name' => 'Privileg', 'Available' => 'Rechte', 'Option' => 'Optionen' )
            )
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
        array_walk( $tblRightListAvailable, function ( TblAccessRight &$V, $I, $B ) {

            $Id = new InputHidden( 'Id' );
            $Id->setDefaultValue( $B[0], true );
            $Right = new InputHidden( 'Right' );
            $Right->setDefaultValue( $V->getId(), true );

            /** @noinspection PhpUndefinedFieldInspection */
            $V->Option = new GridLayoutRight(
                new FormDefault( new GridFormGroup( new GridFormRow( new GridFormCol( array(
                    $Id,
                    $Right,
                    new ButtonSubmitSuccess( 'Hinzufügen' )
                ) ) ) ),
                    null, '/Sphere/System/Authorization/Privilege/Right'
                )
            );
        }, array( $Id, self::getUrlBase() ) );

        /** @noinspection PhpUnusedParameterInspection */
        array_walk( $tblRightList, function ( TblAccessRight &$V, $I, $B ) {

            $Id = new InputHidden( 'Id' );
            $Id->setDefaultValue( $B[0], true );
            $Right = new InputHidden( 'Right' );
            $Right->setDefaultValue( $V->getId(), true );
            $Remove = new InputHidden( 'Remove' );
            $Remove->setDefaultValue( 1, true );

            /** @noinspection PhpUndefinedFieldInspection */
            $V->Option = new GridLayoutRight(
                new FormDefault( new GridFormGroup( new GridFormRow( new GridFormCol( array(
                    $Id,
                    $Right,
                    $Remove,
                    new ButtonSubmitDanger( 'Entfernen' )
                ) ) ) ),
                    null, '/Sphere/System/Authorization/Privilege/Right'
                )
            );
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
