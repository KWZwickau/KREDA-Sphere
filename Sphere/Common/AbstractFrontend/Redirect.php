<?php
namespace KREDA\Sphere\Common\AbstractFrontend;

use KREDA\Sphere\Client\Component\Element\Element;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class Redirect
 *
 * @package KREDA\Sphere\Common\AbstractFrontend
 */
class Redirect extends Element
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
