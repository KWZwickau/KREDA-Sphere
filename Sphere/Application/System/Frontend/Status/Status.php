<?php
namespace KREDA\Sphere\Application\System\Frontend\Status;

use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Status
 *
 * @package KREDA\Sphere\Application\System\Frontend\Status
 */
class Status extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageDatabaseStatus()
    {

        $View = new Stage();
        $View->setTitle( 'Datenbank-Cluster' );
        $View->setDescription( 'Status' );
        $View->setMessage( 'Zeigt die aktuelle Konfiguration und den Verbindungsstatus' );
        $View->setContent(
            new Database\Status(
                System::serviceDatabase()->executeDatabaseStatus()
            )
        );
        return $View;
    }

}
