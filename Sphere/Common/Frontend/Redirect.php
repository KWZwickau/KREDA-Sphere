<?php
namespace KREDA\Sphere\Common\Frontend;

use KREDA\Sphere\Client\Component\Element\Element;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class Redirect
 *
 * @package KREDA\Sphere\Common\Frontend
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

        $this->Template = $this->extensionTemplate( __DIR__.'/Redirect.twig' );
        $this->Template->setVariable( 'Route', '/'.trim( $Route, '/' ) );
        $this->Template->setVariable( 'Timeout', $Timeout );
        $this->Template->setVariable( 'UrlBase', $this->extensionRequest()->getUrlBase() );

    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }
}
