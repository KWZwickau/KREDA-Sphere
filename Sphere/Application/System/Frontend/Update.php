<?php
namespace KREDA\Sphere\Application\System\Frontend;

use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Update
 *
 * @package KREDA\Sphere\Application\System\Installer
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
    public static function stagePerform()
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Update' );
        $View->setContent( System::serviceUpdate()->setupDatabaseSchema( false ) );
        return $View;
    }

}
