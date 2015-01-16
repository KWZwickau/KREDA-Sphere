<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Application\System\Frontend\Authorization\Summary\Summary;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\AbstractFrontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\AbstractFrontend\Form\Element\InputText;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\AbstractFrontend\Table\Structure\TableFromData;

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
            .new TableFromData( Gatekeeper::serviceAccess()->entityRightAll(), 'Überwachte Rechte <small>Routen</small>' )
        );
        return $View;
    }

    /**
     * @param null|string $Access
     *
     * @return Stage
     */
    public static function stageAuthorizationRight( $Access )
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Rechte' );
        $View->setContent(
            new TableFromData( Gatekeeper::serviceAccess()->entityRightAll(), 'Bestehende Rechte <small>Routen</small>' )
            .Gatekeeper::serviceAccess()->executeCreateApplicationRoute(
                new FormDefault(
                    new GridFormGroup(
                        new GridFormRow(
                            new GridFormCol(
                                new InputText(
                                    'Access', 'Route', 'Name'
                                )
                            )
                        )
                    ,'Recht anlegen <small>Route</small>')
                , new ButtonSubmitPrimary( 'Hinzufügen' )
                )
            ,$Access)
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
        $View->setContent(
            new FormDefault( array(
                new GridFormGroup(
                    new GridFormRow(
                        new GridFormCol(
                            new TableFromData( Gatekeeper::serviceAccess()->entityPrivilegeAll() )
                        )
                    )
                ,'Bestehende Privilegien <small>Rechtegruppen</small>'),
                new GridFormGroup(
                    new GridFormRow(
                        new GridFormCol(
                            new InputText(
                                'AccessPrivilege', 'Name', 'Rechtegruppe'
                            )
                        )
                    )
                ,'Privileg anlegen <small>Rechtegruppe</small>')
            ), new ButtonSubmitPrimary( 'Hinzufügen' ))
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
        $View->setContent(
            new FormDefault( array(
                new GridFormGroup(
                    new GridFormRow(
                        new GridFormCol(
                            new TableFromData( Gatekeeper::serviceAccess()->entityAccessAll() )
                        )
                    )
                ,'Bestehende Zugriffslevel <small>Privilegiengruppen</small>'),
                new GridFormGroup(
                    new GridFormRow(
                        new GridFormCol(
                            new InputText(
                                'Access', 'Name', 'Privilegiengruppe'
                            )
                        )
                    )
                ,'Zugriffslevel anlegen <small>Privilegiengruppe</small>')
            ), new ButtonSubmitPrimary( 'Hinzufügen' ))
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
        $View->setContent(
            new FormDefault( array(
                new GridFormGroup(
                    new GridFormRow(
                        new GridFormCol(
                            new TableFromData( Gatekeeper::serviceAccount()->entityAccountRoleAll() )
                        )
                    )
                , 'Bestehende Rollen <small>Zugriffslevelgruppen</small>'),
                new GridFormGroup(
                    new GridFormRow(
                        new GridFormCol(
                            new InputText(
                                'Access', 'Name', 'Zugriffslevelgruppe'
                            )
                        )
                    )
                ,'Rolle anlegen <small>Zugriffslevelgruppe</small>')
            ), new ButtonSubmitPrimary( 'Hinzufügen' ))
        );
        return $View;
    }

}
