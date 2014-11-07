<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use KREDA\Sphere\Application\Service;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use MOC\V\Component\Database\Component\Parameter\Repository\DriverParameter;

class Access extends Service
{

    public function __construct()
    {

        $this->registerDatabaseMaster( 'root', 'kuw', 'ziel2', DriverParameter::DRIVER_PDO_MYSQL, '192.168.100.204' );
    }

    public function apiMain()
    {

        $View = new Landing();
        $View->setTitle( 'Access' );
        $View->setMessage( 'YubiKey' );

        ob_start();
        include( __DIR__.'/Access/demo.php' );
        $Content = ob_get_clean();
        ob_end_clean();

        $View->setContent( $Content );
        return $View;
    }

}
