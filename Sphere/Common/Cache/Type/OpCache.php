<?php
namespace KREDA\Sphere\Common\Cache\Type;

use KREDA\Sphere\Common\Cache\ICacheInterface;

/**
 * Class OpCache
 *
 * @package KREDA\Sphere\Common\Cache\Type
 */
class OpCache implements ICacheInterface
{

    /** @var array $Status */
    private $Status = array();
    /** @var array $Config */
    private $Config = array();

    /**
     *
     */
    function __construct()
    {

        if (function_exists( 'opcache_get_status' )) {
            $this->Status = opcache_get_status();
        }
        if (function_exists( 'opcache_get_configuration' )) {
            $this->Config = opcache_get_configuration();
        }
    }

    /**
     * @return void
     */
    public static function clearCache()
    {

        opcache_reset();
    }

    /**
     * @return integer
     */
    public function getCountHits()
    {

        return $this->Status['opcache_statistics']['hits'];
    }

    /**
     * @return integer
     */
    public function getCountMisses()
    {

        return $this->Status['opcache_statistics']['misses'];
    }

    /**
     * @return integer
     */
    public function getSizeUsed()
    {

        return $this->Status['memory_usage']['used_memory'];
    }

    /**
     * @return integer
     */
    public function getSizeAvailable()
    {

        return $this->Config['directives']['opcache.memory_consumption'];
    }

    /**
     * @return integer
     */
    public function getSizeFree()
    {

        return $this->Status['memory_usage']['free_memory'];
    }

    /**
     * @return integer
     */
    public function getSizeWasted()
    {

        return $this->Status['memory_usage']['wasted_memory'];
    }

}
