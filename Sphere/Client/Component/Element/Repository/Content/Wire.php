<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Content;

/**
 * Class Wire
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Content
 */
class Wire extends Stage
{

    /**
     * @param string $Content
     */
    function __construct( $Content )
    {

        parent::__construct();

        $this->setTitle( 'KREDA Wire' );
        $this->setDescription( 'Aktion nicht durchführbar' );
        $this->setContent( $Content );
    }
}
