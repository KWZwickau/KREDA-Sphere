<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Type;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;

/**
 * Class LayoutList
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Type
 */
class LayoutList extends AbstractType
{

    /** @var array $LinkList */
    private $LinkList = array();
    /** @var array $ContentList */
    private $ContentList = array();

    /**
     * @param array $TextList
     */
    public function __construct( $TextList = array() )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/LayoutList.twig' );
        $this->Template->setVariable( 'TextList', $TextList );
    }

    /**
     * @param string $Title
     * @param string $Target
     *
     * @return $this
     */
    public function addLinkList( $Title, $Target )
    {

        $this->LinkList[] = array( 'Target' => $Target, 'Title' => $Title );
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'LinkList', $this->LinkList );
        $this->Template->setVariable( 'ContentList', $this->ContentList );

        return parent::getContent();
    }

}
