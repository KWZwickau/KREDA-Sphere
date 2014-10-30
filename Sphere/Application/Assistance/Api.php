<?php
namespace KREDA\Sphere\Application\Assistance;

use KREDA\Sphere\Application;
use KREDA\Sphere\Application\Assistance\Client\Nuff;
use MOC\V\Component\Router\Component\IBridgeInterface;
use MOC\V\Component\Router\Component\Parameter\Repository\RouteParameter;

class Api extends Application
{

    public function Main()
    {

        return new Nuff();
    }

    public static function registerApplication( IBridgeInterface $Router )
    {
        $Assistance = new RouteParameter( '/A', 'KREDA\Sphere\Application\Assistance\Api::Main' );
        $Router->addRoute( $Assistance );
    }

}
