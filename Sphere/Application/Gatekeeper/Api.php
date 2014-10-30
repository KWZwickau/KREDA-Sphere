<?php
namespace KREDA\Sphere\Application\Gatekeeper;

use KREDA\Sphere\Application\Gatekeeper\Client\Nuff;

class Api
{

    public function Main()
    {
         return new Nuff();
    }
}
