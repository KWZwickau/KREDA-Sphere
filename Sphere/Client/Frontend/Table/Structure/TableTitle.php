<?php
namespace KREDA\Sphere\Client\Frontend\Table\Structure;

use KREDA\Sphere\Client\Frontend\Table\AbstractTable;

/**
 * Class TableTitle
 *
 * @package KREDA\Sphere\Client\Frontend\Table\Structure
 */
class TableTitle extends AbstractTable
{

    /** @var string $Title */
    private $Title = '';
    /** @var string $Description */
    private $Description = '';

    /**
     * @param string $Title
     * @param string $Description
     */
    public function __construct( $Title, $Description = '' )
    {

        $this->Title = strip_tags( $Title );
        $this->Description = strip_tags( $Description );
    }

    /**
     * @return string
     */
    function __toString()
    {

        if (empty( $this->Description )) {
            return '<h4>'.$this->Title.'</h4>';
        } else {
            return '<h4>'.$this->Title.' <small>'.$this->Description.'</small></h4>';
        }
    }
}
