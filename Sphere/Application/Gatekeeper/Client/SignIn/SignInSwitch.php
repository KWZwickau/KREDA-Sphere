<?php
namespace KREDA\Sphere\Application\Gatekeeper\Client\SignIn;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class SignInSwitch
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Client\SignIn
 */
class SignInSwitch extends Shell implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    /**
     * @throws TemplateTypeException
     */
    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/SignInSwitch.twig' );
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
