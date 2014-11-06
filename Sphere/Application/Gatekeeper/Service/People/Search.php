<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\People;

use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Template;

class Search extends Element implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    /** @var string $Category */
    private $Category = '{ SearchCategory }';
    /** @var string $Create */
    private $Create = '{ RouteCreate }';

    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/Search.twig' );
    }

    /**
     * @param string $Value
     *
     * @return Search
     */
    public function setCategory( $Value )
    {

        $this->Category = $Value;
        return $this;
    }

    /**
     * @param string $Value
     *
     * @return Search
     */
    public function setRouteCreate( $Value )
    {

        $this->Create = $Value;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'SearchCategory', $this->Category );
        $this->Template->setVariable( 'RouteCreate', $this->Create );
        return $this->Template->getContent();
    }

}
