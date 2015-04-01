<?php
namespace KREDA\Sphere\Client\Frontend\Table\Structure;

use KREDA\Sphere\Client\Frontend\Table\AbstractTable;

/**
 * Class TableRow
 *
 * @package KREDA\Sphere\Client\Frontend\Form\Type
 */
class TableRow extends AbstractTable
{

    /** @var TableColumn[] $TableColumn */
    private $TableColumn = array();

    /**
     * @param TableColumn|TableColumn[] $TableColumn
     */
    function __construct( $TableColumn )
    {

        if (!is_array( $TableColumn )) {
            $TableColumn = array( $TableColumn );
        }
        $this->TableColumn = $TableColumn;
    }

    /**
     * @return TableColumn[]
     */
    public function getTableColumn()
    {

        return $this->TableColumn;
    }
}
