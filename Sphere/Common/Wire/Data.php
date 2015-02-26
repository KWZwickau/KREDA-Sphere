<?php
namespace KREDA\Sphere\Common\Wire;

/**
 * Class Data
 *
 * @package KREDA\Sphere\Common\Wire
 */
class Data
{

    /** @var int $Id */
    private $Id = 0;

    /**
     * @param int $Id
     */
    function __construct( $Id )
    {

        $this->Id = $Id;
    }

    /**
     * @return int
     */
    public function getId()
    {

        return $this->Id;
    }

}
