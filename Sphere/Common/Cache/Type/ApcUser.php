<?php
namespace KREDA\Sphere\Common\Cache\Type;

use KREDA\Sphere\Common\Cache\ICacheInterface;

/**
 * Class ApcUser
 *
 * @package KREDA\Sphere\Common\Cache\Type
 */
class ApcUser implements ICacheInterface
{

    /** @var array $Status */
    private $Status = array();

    /**
     *
     */
    function __construct()
    {

        if (function_exists( 'apc_cache_info' )) {
            $this->Status = apc_cache_info( 'user', true );
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

        return $this->Status['nhits'];
    }

    /**
     * @return integer
     */
    public function getCountMisses()
    {

        return $this->Status['nmisses'];
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

        return $this->Status['mem_size'];
    }

    /**
     * @return integer
     */
    public function getSizeFree()
    {

        return -1;
    }

    /**
     * @return integer
     */
    public function getSizeWasted()
    {

        return -1;
    }
}
