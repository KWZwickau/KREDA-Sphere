<?php
namespace KREDA\Sphere\Client\Frontend\Table\Structure;

use KREDA\Sphere\Client\Frontend\Table\AbstractTable;

/**
 * Class TableHead
 *
 * @package KREDA\Sphere\Client\Frontend\Table\Structure
 */
class TableHead extends AbstractTable
{

    /** @var TableRow[] $TableRow */
    private $TableRow = array();

    /**
     * @param null|TableRow|TableRow[] $TableRow
     */
    function __construct( $TableRow = null )
    {

        if (null !== $TableRow && !is_array( $TableRow )) {
            $TableRow = array( $TableRow );
        } elseif (null === $TableRow) {
            $TableRow = array();
        }
        $this->TableRow = $TableRow;
    }

    /**
     * @return TableRow[]
     */
    public function getTableRow()
    {

        return $this->TableRow;
    }
}
