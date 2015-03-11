<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
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
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutRow;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutTitle;
use KREDA\Sphere\Common\Frontend\Redirect;
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
        array_walk( $AccessList, function ( TblAccess &$V, $I, $B ) {

            $Id = new InputHidden( 'Id' );
            $Id->setDefaultValue( $V->getId(), true );

            $LinkList = Gatekeeper::serviceAccess()->entityPrivilegeAllByAccess( $V );
            if (empty( $LinkList )) {
                $V->Available = new MessageWarning( 'Keine Privilegien vergeben' );
            } else {
                $V->Available = new TableData( $LinkList, null, array( 'Name' => 'Privilegien' ), false );
            }

            $V->Option = ''
                .new FormDefault( new GridFormGroup( new GridFormRow( new GridFormCol( array(
                    $Id,
                    new ButtonSubmitPrimary( 'Privilegien bearbeiten' )
                ) ) ) ), null, $B.'/Sphere/System/Authorization/Access/Privilege' );

        }, self::getUrlBase() );

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
                    , new ButtonSubmitPrimary( 'Hinzufügen' )
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
}
