<?php
namespace KREDA\Sphere\Application\Gatekeeper\Authentication\Common;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class Redirect
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Client\Redirect
 */
class Redirect extends Shell implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    /**
     * @param string $Route
     * @param int    $Timeout
     *
     * @throws TemplateTypeException
     */
    function __construct( $Route, $Timeout = 15 )
    {

        $this->Template = Template::getTemplate( __DIR__.'/Redirect.twig' );
        $this->Template->setVariable( 'Route', '/'.trim( $Route, '/' ) );
        $this->Template->setVariable( 'Timeout', $Timeout );
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
