<?php
namespace KREDA\Sphere\Application\Gatekeeper\Client;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;

class Main extends Shell implements IElementInterface
{

    /**
     * @return string
     */
    public function getContent()
    {

        return __CLASS__;
    }

}
