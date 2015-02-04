<?php
namespace KREDA\Sphere\Common\Frontend\Table\Structure;

use KREDA\Sphere\Common\Frontend\Table\AbstractTable;

/**
 * Class GridTableCol
 *
 * @package KREDA\Sphere\Common\Frontend\Table\Structure
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

        /**
         * Remove "small" from child tables
         */
        $Content = preg_replace(
            '!<table(.*?)class="(.*?)\ssmall"(.*?)>!is',
            '<table${1}class="${2}"${3}>', $Content );

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

        return (string)$this->Content;
    }

    /**
     * @return string
     */
    public function getWidth()
    {

        return $this->GridWidth;
    }
}
