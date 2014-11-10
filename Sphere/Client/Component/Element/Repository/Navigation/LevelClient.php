<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Navigation;

use KREDA\Sphere\Client\Component\Element\Repository\Navigation;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Template;

/**
 * Class LevelClient
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Navigation
 */
class LevelClient extends Navigation implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;
    /** @var LevelClient\Link[] $MainLinkList */
    private $MainLinkList = array();
    /** @var LevelClient\Link[] $MetaLinkList */
    private $MetaLinkList = array();

    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/LevelClient/Main.twig' );
        //$this->Template->setVariable( 'PathBase', HttpKernel::getRequest()->getPathBase() );

    }

    /**
     * @param LevelClient\Link $Link
     *
     * @return LevelClient
     */
    public function addLinkToMain( LevelClient\Link $Link )
    {

        array_push( $this->MainLinkList, $Link->getContent() );
        return $this;
    }

    /**
     * @param LevelClient\Link $Link
     *
     * @return LevelClient
     */
    public function addLinkToMeta( LevelClient\Link $Link )
    {

        array_push( $this->MetaLinkList, $Link->getContent() );
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'PositionMain', implode( '', $this->MainLinkList ) );
        $this->Template->setVariable( 'PositionMeta', implode( '', $this->MetaLinkList ) );
        return $this->Template->getContent();
    }

}
