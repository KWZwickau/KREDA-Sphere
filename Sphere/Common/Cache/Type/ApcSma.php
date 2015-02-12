<?php
namespace KREDA\Sphere\Common\Cache\Type;

use KREDA\Sphere\Common\Cache\ICacheInterface;

/**
 * Class ApcSma
 *
 * @package KREDA\Sphere\Common\Cache\Type
 */
class ApcSma implements ICacheInterface
{

    /** @var array $Status */
    private $Status = array();

    /**
     *
     */
    function __construct()
    {

        if (function_exists( 'apc_sma_info' )) {
            $this->Status = apc_sma_info( true );
        }
    }

    /**
     * @return void
     */
    public static function clearCache()
    {

        apc_clear_cache();
    }

    /**
     * @return integer
     */
    public function getCountHits()
    {

        return -1;
    }

    /**
     * @return integer
     */
    public function getCountMisses()
    {

        return -1;
    }

    /**
     * @return integer
     */
    public function getSizeUsed()
    {

        return $this->getSizeAvailable() - $this->getSizeFree();
    }

    /**
     * @return integer
     */
    public function getSizeAvailable()
    {

        return $this->Status['seg_size'];
    }

    /**
     * @return integer
     */
    public function getSizeFree()
    {

        return $this->Status['avail_mem'];
    }

    /**
     * @return integer
     */
    public function getSizeWasted()
    {

        return -1;
    }
}
