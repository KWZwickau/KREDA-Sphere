<?php
namespace KREDA\Sphere\Client\Frontend\Table\Type;

use KREDA\Sphere\Client\Frontend\Table\AbstractTable;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableBody;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableFoot;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableHead;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableTitle;

/**
 * Class Table
 *
 * @package KREDA\Sphere\Common\Frontend\Table
 */
class Table extends AbstractTable
{

    /**
     * @param TableHead  $TableHead
     * @param TableBody  $TableBody
     * @param TableTitle $TableTitle
     * @param bool|array $Interactive
     * @param TableFoot  $TableFoot
     */
    public function __construct(
        TableHead $TableHead,
        TableBody $TableBody,
        TableTitle $TableTitle = null,
        $Interactive = false,
        TableFoot $TableFoot = null
    ) {

        if (!is_array( $TableHead )) {
            $TableHead = array( $TableHead );
        }
        $this->TableHead = $TableHead;
        if (!is_array( $TableBody )) {
            $TableBody = array( $TableBody );
        }
        $this->TableBody = $TableBody;
        if (!is_array( $TableFoot )) {
            $TableFoot = array( $TableFoot );
        }
        $this->TableFoot = $TableFoot;
        if ($Interactive) {
            $this->Template = $this->extensionTemplate( __DIR__.'/TableData.twig' );
            if (is_array( $Interactive )) {
                $this->Template->setVariable( 'InteractiveOption', json_encode( $Interactive ) );
            }
        } else {
            $this->Template = $this->extensionTemplate( __DIR__.'/Table.twig' );
        }
        $this->Template->setVariable( 'TableTitle', $TableTitle );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'HeadList', $this->TableHead );
        $this->Template->setVariable( 'BodyList', $this->TableBody );
        $this->Template->setVariable( 'FootList', $this->TableFoot );
        $this->Template->setVariable( 'Hash', $this->getHash() );
        return $this->Template->getContent();
    }

}
