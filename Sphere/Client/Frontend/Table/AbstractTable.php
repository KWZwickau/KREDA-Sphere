<?php
namespace KREDA\Sphere\Client\Frontend\Table;

use KREDA\Sphere\Client\Frontend\IElementInterface;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableBody;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableFoot;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableHead;
use KREDA\Sphere\Common\AbstractFrontend;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractTable
 *
 * @package KREDA\Sphere\Common\Frontend\Table
 */
abstract class AbstractTable extends AbstractFrontend implements IElementInterface
{

    /** @var TableHead[] $TableHead */
    protected $TableHead = array();
    /** @var TableBody[] $TableBody */
    protected $TableBody = array();
    /** @var TableFoot[] $TableFoot */
    protected $TableFoot = array();
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
            $HeadList = $this->TableHead;
            array_walk( $HeadList, function ( &$H ) {

                if (is_object( $H )) {
                    $H = serialize( $H );
                }
            } );
            $BodyList = $this->TableBody;
            array_walk( $BodyList, function ( &$H ) {

                if (is_object( $H )) {
                    $H = serialize( $H );
                }
            } );
            $FootList = $this->TableFoot;
            array_walk( $FootList, function ( &$H ) {

                if (is_object( $H )) {
                    $H = serialize( $H );
                }
            } );
            $this->Hash = sha1( json_encode( $HeadList ).json_encode( $BodyList ).json_encode( $FootList ) );
        }
        return $this->Hash;
    }

    /**
     * @param TableHead $TableHead
     */
    public function appendHead( TableHead $TableHead )
    {

        array_push( $this->TableHead, $TableHead );
    }

    /**
     * @param TableHead $TableHead
     */
    public function prependHead( TableHead $TableHead )
    {

        array_unshift( $this->TableHead, $TableHead );
    }

    /**
     * @param TableBody $TableBody
     */
    public function appendBody( TableBody $TableBody )
    {

        array_push( $this->TableBody, $TableBody );
    }

    /**
     * @param TableBody $TableBody
     */
    public function prependBody( TableBody $TableBody )
    {

        array_unshift( $this->TableBody, $TableBody );
    }

    /**
     * @param TableFoot $TableFoot
     */
    public function appendFoot( TableFoot $TableFoot )
    {

        array_push( $this->TableFoot, $TableFoot );
    }

    /**
     * @param TableFoot $TableFoot
     */
    public function prependFoot( TableFoot $TableFoot )
    {

        array_unshift( $this->TableFoot, $TableFoot );
    }

    /**
     * @return string
     */
    public function getName()
    {

        return '';
    }
}
