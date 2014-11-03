<?php
namespace KREDA\Sphere\Application\Assistance\Client;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;

class Nuff extends Shell implements IElementInterface
{

    /**
     * @return string
     */
    public function getContent()
    {

        return __NAMESPACE__;
    }

}
