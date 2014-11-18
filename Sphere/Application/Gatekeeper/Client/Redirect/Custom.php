<?php
namespace KREDA\Sphere\Application\Gatekeeper\Client\Redirect;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class Custom
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Client\Redirect
 */
class Custom extends Shell implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    /**
     * @param string $Title
     * @param string $Route
     * @param string $Description
     * @param string $Message
     * @param int    $Timeout
     *
     * @throws TemplateTypeException
     */
    function __construct( $Title, $Route, $Description = '', $Message = '', $Timeout = 15 )
    {

        $this->Template = Template::getTemplate( __DIR__.'/Custom.twig' );
        $this->Template->setVariable( 'Title', $Title );
        $this->Template->setVariable( 'Route', '/'.trim( $Route, '/' ) );
        $this->Template->setVariable( 'Description', $Description );
        $this->Template->setVariable( 'Message', $Message );
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
