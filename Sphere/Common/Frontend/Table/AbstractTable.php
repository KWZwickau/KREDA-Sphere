<?php
namespace KREDA\Sphere\Common\Frontend\Table;

use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\IElementInterface;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableBody;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableFoot;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableHead;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractTable
 *
 * @package KREDA\Sphere\Common\Frontend\Table
 */
abstract class AbstractTable extends AbstractFrontend implements IElementInterface
{

    /** @var GridTableHead[] $GridHeadList */
    protected $GridHeadList = array();
    /** @var GridTableBody[] $GridBodyList */
    protected $GridBodyList = array();
    /** @var GridTableFoot[] $GridFootList */
    protected $GridFootList = array();
    /** @var IBridgeInterface $Template */
    protected $Template = null;
    /** @var string $Hash */
    protected $Hash = '';

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }

    /**
     * @return string
     */
    public function getHash()
    {

        if (empty( $this->Hash )) {
            $this->Hash = sha1( json_encode( $this->GridHeadList ).json_encode( $this->GridBodyList ).json_encode( $this->GridFootList ) );
        }
        return $this->Hash;
    }

    /**
     * @param GridTableHead $GridHead
     */
    public function appendGridHead( GridTableHead $GridHead )
    {

        array_push( $this->GridHeadList, $GridHead );
    }

    /**
     * @param GridTableHead $GridHead
     */
    public function prependGridHead( GridTableHead $GridHead )
    {

        array_unshift( $this->GridHeadList, $GridHead );
    }

    /**
     * @param GridTableBody $GridBody
     */
    public function appendGridBody( GridTableBody $GridBody )
    {

        array_push( $this->GridBodyList, $GridBody );
    }

    /**
     * @param GridTableBody $GridBody
     */
    public function prependGridBody( GridTableBody $GridBody )
    {

        array_unshift( $this->GridBodyList, $GridBody );
    }

    /**
     * @param GridTableFoot $GridFoot
     */
    public function appendGridFoot( GridTableFoot $GridFoot )
    {

        array_push( $this->GridFootList, $GridFoot );
    }

    /**
     * @param GridTableFoot $GridFoot
     */
    public function prependGridFoot( GridTableFoot $GridFoot )
    {

        array_unshift( $this->GridFootList, $GridFoot );
    }

    /**
     * @return string
     */
    public function getName()
    {

        return '';
    }
}
