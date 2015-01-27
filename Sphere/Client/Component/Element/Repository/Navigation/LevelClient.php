<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Navigation;

use KREDA\Sphere\Client\Component\Element\Repository\AbstractNavigation;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class LevelClient
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Navigation
 */
class LevelClient extends AbstractNavigation
{

    /** @var IBridgeInterface $Template */
    private $Template = null;
    /** @var LevelClient\Link[] $MainLinkList */
    private $MainLinkList = array();
    /** @var LevelClient\Link[] $MetaLinkList */
    private $MetaLinkList = array();
    /** @var array $BreadcrumbList */
    private $BreadcrumbList = array();

    /**
     * @throws TemplateTypeException
     */
    function __construct()
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/LevelClient/Main.twig' );
    }

    /**
     * @param string $Title
     *
     * @return LevelClient
     */
    public function addBreadcrumb( $Title )
    {

        if (!in_array( $Title, $this->BreadcrumbList )) {
            array_push( $this->BreadcrumbList, $Title );
        }
        return $this;
    }

    /**
     * @param LevelClient\Link $Link
     *
     * @return LevelClient
     */
    public function addLinkToMain( LevelClient\Link $Link )
    {

        if (!in_array( $Link->getContent(), $this->MainLinkList )) {
            array_push( $this->MainLinkList, $Link->getContent() );
        }
        return $this;
    }

    /**
     * @param LevelClient\Link $Link
     *
     * @return LevelClient
     */
    public function addLinkToMeta( LevelClient\Link $Link )
    {

        if (!in_array( $Link->getContent(), $this->MetaLinkList )) {
            array_push( $this->MetaLinkList, $Link->getContent() );
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'PositionMain', implode( '', $this->MainLinkList ) );
        $this->Template->setVariable( 'PositionMeta', implode( '', $this->MetaLinkList ) );
        $this->Template->setVariable( 'PositionBreadcrumb',
            ( empty( $this->BreadcrumbList ) ? '' : implode( '', $this->BreadcrumbList ).'&nbsp;&nbsp;' ) );
        return $this->Template->getContent();
    }

}
