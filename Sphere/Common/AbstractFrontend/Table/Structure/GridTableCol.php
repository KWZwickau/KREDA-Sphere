<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Table\Structure;

use KREDA\Sphere\Common\AbstractFrontend\Table\AbstractTable;

/**
 * Class GridTableCol
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Table\Structure
 */
class GridTableCol extends AbstractTable
{

    /** @var string $Content */
    private $Content = '';

    /**
     * @param string $Content
     */
    function __construct( $Content )
    {

        $this->Content = $Content;
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Content;
    }

}
