<?php
namespace KREDA\Sphere\Client\Frontend\Button\Structure;

use KREDA\Sphere\Client\Frontend\Button\AbstractType;

/**
 * Class ButtonGroup
 *
 * @package KREDA\Sphere\Client\Frontend\Button\Structure
 */
class ButtonGroup extends AbstractType
{

    /** @var AbstractType[] $ButtonList */
    protected $ButtonList = array();

    /**
     * @param AbstractType|AbstractType[] $ButtonList
     */
    function __construct( $ButtonList )
    {

        if (!is_array( $ButtonList )) {
            $ButtonList = array( $ButtonList );
        }
        $this->ButtonList = $ButtonList;

        $this->Template = $this->extensionTemplate( __DIR__.'/ButtonGroup.twig' );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'ButtonList', $this->ButtonList );
        return $this->Template->getContent();
    }
}
