<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Navigation;

use KREDA\Sphere\Client\Component\Element\Repository\Navigation;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class LevelApplication
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Navigation
 */
class LevelApplication extends Navigation implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;
    /** @var LevelApplication\Link[] $MainLinkList */
    private $MainLinkList = array();
    /** @var array $BreadcrumbList */
    private $BreadcrumbList = array();

    /**
     * @throws TemplateTypeException
     */
    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/LevelApplication/Main.twig' );
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
     * @param LevelApplication\Link $Link
     *
     * @return LevelApplication
     */
    public function addLinkToMain( LevelApplication\Link $Link )
    {

        if (!in_array( $Link->getContent(), $this->MainLinkList )) {
            array_push( $this->MainLinkList, $Link->getContent() );
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'PositionMain', implode( '', $this->MainLinkList ) );
        $this->Template->setVariable( 'PositionBreadcrumb',
            ( empty( $this->BreadcrumbList ) ? '' : implode( '', $this->BreadcrumbList ).'&nbsp;&nbsp;' ) );
        return $this->Template->getContent();
    }

}
