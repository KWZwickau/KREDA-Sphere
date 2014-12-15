<?php
namespace KREDA\Sphere\Application\System\Installer;

use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Update
 *
 * @package KREDA\Sphere\Application\System\Installer
 */
class Installer extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function guiSummary()
    {

        $View = new Landing();
        $View->setTitle( 'KREDA Update' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function guiUpdateSimulation()
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Update' );
        $View->setDescription( 'Simulation' );
        $View->setMessage( '' );
        $View->setContent( System::serviceUpdate()->setupDatabaseSchema( true ) );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function guiUpdatePerform()
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Update' );
        $View->setDescription( '' );
        $View->setMessage( '' );
        $View->setContent( System::serviceUpdate()->setupDatabaseSchema( false ) );
        return $View;
    }

}
