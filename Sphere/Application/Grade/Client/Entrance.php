<?php
namespace KREDA\Sphere\Application\Grade\Client;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

class Entrance extends Shell implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/Entrance.twig' );
        $this->Template->setVariable( 'UrlBase', HttpKernel::getRequest()->getUrlBase() );

    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }

}
