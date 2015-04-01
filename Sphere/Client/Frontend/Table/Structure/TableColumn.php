<?php
namespace KREDA\Sphere\Client\Frontend\Table\Structure;

use KREDA\Sphere\Client\Frontend\Table\AbstractTable;

/**
 * Class TableColumn
 *
 * @package KREDA\Sphere\Client\Frontend\Table\Structure
 */
class TableColumn extends AbstractTable
{

    /** @var string $Content */
    private $Content = '';
    /** @var int $Size */
    private $Size = 1;
    /** @var string $Width */
    private $Width = 'auto';

    /**
     * @param string $Content
     * @param int    $Size
     * @param string $Width
     */
    function __construct( $Content, $Size = 1, $Width = 'auto' )
    {

        if (is_object( $Content ) && $Content instanceof \DateTime) {
            $Content = $Content->format( 'd.m.Y H:i:s' );
        }
        /**
         * Remove "small" from child tables
         */
        $Content = preg_replace(
            '!<table(.*?)class="(.*?)\ssmall"(.*?)>!is',
            '<table${1}class="${2}"${3}>', $Content );
        $this->Content = $Content;
        $this->Size = $Size;
        $this->Width = $Width;
    }

    /**
     * @return int
     */
    public function getSize()
    {

        return $this->Size;
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

        return $this->Width;
    }
}
