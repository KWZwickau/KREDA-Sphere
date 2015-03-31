<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Type;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;

/**
 * Class Grid
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Type
 */
class Grid extends AbstractType
{

    /**
     * @param Group|Group[] $Group
     */
    function __construct( $Group )
    {

        if (!is_array( $Group )) {
            $Group = array( $Group );
        }
        $this->Group = $Group;
        $this->Template = $this->extensionTemplate( __DIR__.'/Grid.twig' );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'Grid', $this->Group );
        return $this->Template->getContent();
    }
}
