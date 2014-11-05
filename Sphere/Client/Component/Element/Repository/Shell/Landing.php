<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Shell;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class Landing
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Shell
 */
class Landing extends Shell implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    /** @var string $Title */
    private $Title = '{ LandingTitle }';
    /** @var string $Message */
    private $Message = '{ LandingMessage }';
    /** @var string $Content */
    private $Content = '';

    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/Landing.twig' );
    }

    /**
     * @param string $Value
     *
     * @return Landing
     */
    public function setTitle( $Value )
    {

        $this->Title = $Value;
        return $this;
    }

    /**
     * @param string $Value
     *
     * @return Landing
     */
    public function setMessage( $Value )
    {

        $this->Message = $Value;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'LandingTitle', $this->Title );
        $this->Template->setVariable( 'LandingMessage', $this->Message );
        $this->Template->setVariable( 'LandingContent', $this->Content );

        $this->Template->setVariable( 'UrlBase', HttpKernel::getRequest()->getUrlBase() );

        return $this->Template->getContent();
    }

    /**
     * @param string $Value
     *
     * @return Landing
     */
    public function setContent( $Value )
    {

        $this->Content = $Value;
        return $this;
    }

}
