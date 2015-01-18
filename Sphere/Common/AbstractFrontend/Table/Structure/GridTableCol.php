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
    /** @var int $GridSize */
    private $GridSize = 1;
    /** @var string $GridWidth */
    private $GridWidth = 'auto';

    /**
     * @param string $Content
     * @param int    $GridSize
     * @param string $GridWidth
     */
    function __construct( $Content, $GridSize = 1, $GridWidth = 'auto' )
    {

        $this->Content = $Content;
        $this->GridSize = $GridSize;
        $this->GridWidth = $GridWidth;
    }

    /**
     * @return string
     */
    public function getSize()
    {

        return $this->GridSize;
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Content;
    }

    /**
     * @return string
     */
    public function getWidth()
    {

        return $this->GridWidth;
    }
}
