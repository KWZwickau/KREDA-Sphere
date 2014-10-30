<?php
namespace KREDA\Sphere\Application\Assistance\Client;

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

        $Request =
            '<dl class="well-lg">'
            .'<dt>PathInfo</dt>'
            .'<dd>'.HttpKernel::getRequest()->getPathInfo().'</dd>'
            .'<dt>PathBase</dt>'
            .'<dd>'.HttpKernel::getRequest()->getPathBase().'</dd>'
            .'<dt>UrlBase</dt>'
            .'<dd>'.HttpKernel::getRequest()->getUrlBase().'</dd>'
            .'<dt>UrlPort</dt>'
            .'<dd>'.HttpKernel::getRequest()->getPort().'</dd>'
            .'</dl>';

        return $Request;
    }

}
