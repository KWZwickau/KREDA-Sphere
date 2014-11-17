<?php
namespace KREDA\Sphere\Application\Gatekeeper\Client;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class Welcome
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Client
 */
class Welcome extends Shell implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    /**
     * @throws TemplateTypeException
     */
    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/Welcome.twig' );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }
}
