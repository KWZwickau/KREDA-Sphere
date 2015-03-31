<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Type;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;

/**
 * Class Group
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Type
 */
class Group extends AbstractType
{

    /** @var Row[] $Row */
    private $Row = array();
    /** @var string $Title */
    private $Title = '';

    /**
     * @param Row|Row[] $Row
     * @param Title     $Title
     */
    function __construct( $Row, Title $Title = null )
    {

        if (!is_array( $Row )) {
            $Row = array( $Row );
        }
        $this->Row = $Row;
        $this->Title = $Title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {

        return $this->Title;
    }

    /**
     * @return Row[]
     */
    public function getRow()
    {

        return $this->Row;
    }
}
