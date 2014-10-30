<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Shell;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class Screen
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Shell
 */
class Screen extends Shell implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;
    /** @var Container[] $PositionNavigation */
    private $PositionNavigation = array();
    /** @var Container[] $PositionContent */
    private $PositionContent = array();

    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/Screen/Main.twig' );
        $this->Template->setVariable( 'PathBase', HttpKernel::getRequest()->getPathBase() );
    }

    /**
     * @param Container $Container
     *
     * @return Screen
     */
    public function addToNavigation( Container $Container )
    {

        array_push( $this->PositionNavigation, $Container->getContent() );
        return $this;
    }

    /**
     * @param Container $Container
     *
     * @return Screen
     */
    public function addToContent( Container $Container )
    {

        array_push( $this->PositionContent, $Container->getContent() );
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $Request =
            '<div>'
            .'<div>'
            .'<div>'
            .'<div class="navbar-fixed-bottom">'
            .'<dl class="well-lg">'
            .'<dt>PathInfo</dt>'
            .'<dd>'.HttpKernel::getRequest()->getPathInfo().'</dd>'
            .'<dt>PathBase</dt>'
            .'<dd>'.HttpKernel::getRequest()->getPathBase().'</dd>'
            .'<dt>UrlBase</dt>'
            .'<dd>'.HttpKernel::getRequest()->getUrlBase().'</dd>'
            .'<dt>UrlPort</dt>'
            .'<dd>'.HttpKernel::getRequest()->getPort().'</dd>'
            .'</dl>'
            .'</div>'
            .'</div>'
            .'</div>'
            .'</div>';

        $this->Template->setVariable( 'PositionNavigation', implode( '', $this->PositionNavigation ) );
        $this->Template->setVariable( 'PositionContent', implode( '', $this->PositionContent ).$Request );
        return $this->Template->getContent();
    }

}
