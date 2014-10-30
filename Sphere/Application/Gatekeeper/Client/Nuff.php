<?php
namespace KREDA\Sphere\Application\Gatekeeper\Client;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Core\HttpKernel\HttpKernel;

class Nuff extends Shell implements IElementInterface
{

    /**
     * @return string
     */
    public function getContent()
    {

        $Request = 'Gatekeeper';
        return $Request;
    }

}
