<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Type;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;

/**
 * Class Layout
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Type
 */
class Layout extends AbstractType
{

    /**
     * @param LayoutGroup|LayoutGroup[] $LayoutGroup
     */
    function __construct( $LayoutGroup )
    {

        if (!is_array( $LayoutGroup )) {
            $LayoutGroup = array( $LayoutGroup );
        }
        $this->LayoutGroup = $LayoutGroup;
        $this->Template = $this->extensionTemplate( __DIR__.'/Layout.twig' );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'Layout', $this->LayoutGroup );
        return $this->Template->getContent();
    }
}
