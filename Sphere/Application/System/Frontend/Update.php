<?php
namespace KREDA\Sphere\Application\System\Frontend;

use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonDangerLink;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSuccessLink;
use KREDA\Sphere\Common\Frontend\Button\Structure\GroupDefault;

/**
 * Class Update
 *
 * @package KREDA\Sphere\Application\System\Frontend
 */
class Update extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageStatus()
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Update' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        $View->setContent(
            new GroupDefault( array(
                new ButtonSuccessLink(
                    'Simulation', 'System/Update/Simulation', new CogIcon()
                ),
                new ButtonDangerLink(
                    'Installation', 'System/Update/Install', new CogWheelsIcon()
                )
            ) )
        );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageSimulation()
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Update' );
        $View->setDescription( 'Simulation' );
        $View->setContent( System::serviceUpdate()->setupDatabaseSchema( true ) );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageInstall()
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Update' );
        $View->setDescription( 'Installation' );
        $View->setContent( System::serviceUpdate()->setupDatabaseSchema( false ) );
        return $View;
    }

}
