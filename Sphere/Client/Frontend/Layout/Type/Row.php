<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Type;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;

/**
 * Class Row
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Type
 */
class Row extends AbstractType
{

    /** @var Column[] $Column */
    private $Column = array();

    /**
     * @param Column|Column[] $Column
     */
    function __construct( $Column )
    {

        if (!is_array( $Column )) {
            $Column = array( $Column );
        }
        $this->Column = $Column;
    }

    /**
     * @return Column[]
     */
    public function getColumn()
    {

        return $this->Column;
    }
}
