<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Application\System\Frontend\Authorization\Summary\Summary;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableTitle;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;

/**
 * Class Authorization
 *
 * @package KREDA\Sphere\Application\System\Frontend\Authorization
 */
class Authorization extends Role
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

        $Export = self::extensionDocumentExcel( '/Test.csv' );

        $Export->saveFile();

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
                new TableTitle( 'Überwachte Routen', 'Rechte' )
            )
            .new Summary( $TwigData )
        );
        return $View;
    }

}
