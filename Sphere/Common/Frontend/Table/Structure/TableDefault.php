<?php
namespace KREDA\Sphere\Common\Frontend\Table\Structure;

use KREDA\Sphere\Common\Frontend\Table\AbstractTable;

/**
 * Class TableDefault
 *
 * @package KREDA\Sphere\Common\Frontend\Table
 */
class TableDefault extends AbstractTable
{

    /**
     * @param GridTableHead  $GridHead
     * @param GridTableBody  $GridBody
     * @param GridTableTitle $Title
     * @param bool|array     $Interactive
     *
     * @param GridTableFoot  $GridFoot
     */
    function __construct(
        GridTableHead $GridHead,
        GridTableBody $GridBody,
        GridTableTitle $Title = null,
        $Interactive = false,
        GridTableFoot $GridFoot = null
    ) {

        if (!is_array( $GridHead )) {
            $GridHead = array( $GridHead );
        }
        $this->GridHeadList = $GridHead;
        if (!is_array( $GridBody )) {
            $GridBody = array( $GridBody );
        }
        $this->GridBodyList = $GridBody;
        if (!is_array( $GridFoot )) {
            $GridFoot = array( $GridFoot );
        }
        $this->GridFootList = $GridFoot;
        if ($Interactive) {
            $this->Template = $this->extensionTemplate( __DIR__.'/TableInteractive.twig' );
            if (is_array( $Interactive )) {
                $this->Template->setVariable( 'InteractiveOption', json_encode( $Interactive ) );
            }
        } else {
            $this->Template = $this->extensionTemplate( __DIR__.'/TableDefault.twig' );
        }
        $this->Template->setVariable( 'Title', $Title );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'GridHeadList', $this->GridHeadList );
        $this->Template->setVariable( 'GridBodyList', $this->GridBodyList );
        $this->Template->setVariable( 'GridFootList', $this->GridFootList );
        $this->Template->setVariable( 'Hash', $this->getHash() );
        return $this->Template->getContent();
    }

}
