<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Table\Structure;

use KREDA\Sphere\Common\AbstractFrontend\Table\AbstractTable;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class TableDefault
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Table
 */
class TableDefault extends AbstractTable
{

    /**
     * @param GridTableHead $GridHead
     * @param GridTableBody $GridBody
     * @param string        $Title
     * @param bool|array    $Interactive
     *
     * @param GridTableFoot $GridFoot
     *
     * @throws TemplateTypeException
     */
    function __construct(
        GridTableHead $GridHead,
        GridTableBody $GridBody,
        $Title = '',
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
            $this->Template = Template::getTemplate( __DIR__.'/TableInteractive.twig' );
            if (is_array( $Interactive )) {
                $this->Template->setVariable( 'InteractiveOption', json_encode( $Interactive ) );
            }
        } else {
            $this->Template = Template::getTemplate( __DIR__.'/TableDefault.twig' );
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
