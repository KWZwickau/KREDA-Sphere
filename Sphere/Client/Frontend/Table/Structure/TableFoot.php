<?php
namespace KREDA\Sphere\Client\Frontend\Table\Structure;

use KREDA\Sphere\Client\Frontend\Table\AbstractTable;

/**
 * Class TableFoot
 *
 * @package KREDA\Sphere\Client\Frontend\Table\Structure
 */
class TableFoot extends AbstractTable
{

    /** @var TableRow[] $TableRow */
    private $TableRow = array();

    /**
     * @param TableRow|TableRow[] $TableRow
     */
    public function __construct( $TableRow )
    {

        if (!is_array( $TableRow )) {
            $TableRow = array( $TableRow );
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
