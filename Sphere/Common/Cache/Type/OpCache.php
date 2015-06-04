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

        if (function_exists( 'opcache_reset' )) {
            opcache_reset();
        }
    }

    /**
     * @return integer
     */
    public function getCountHits()
    {

        if (!empty( $this->Status )) {
            return $this->Status['opcache_statistics']['hits'];
        }
        return -1;
    }

    /**
     * @return integer
     */
    public function getCountMisses()
    {

        if (!empty( $this->Status )) {
            return $this->Status['opcache_statistics']['misses'];
        }
        return -1;
    }

    /**
     * @return integer
     */
    public function getSizeUsed()
    {

        if (!empty( $this->Status )) {
            return $this->Status['memory_usage']['used_memory'];
        }
        return -1;
    }

    /**
     * @return integer
     */
    public function getSizeAvailable()
    {

        if (!empty( $this->Status )) {
            return $this->Config['directives']['opcache.memory_consumption'];
        }
        return -1;
    }

    /**
     * @return integer
     */
    public function getSizeFree()
    {

        if (!empty( $this->Status )) {
            return $this->Status['memory_usage']['free_memory'];
        }
        return -1;
    }

    /**
     * @return integer
     */
    public function getSizeWasted()
    {

        if (!empty( $this->Status )) {
            return $this->Status['memory_usage']['wasted_memory'];
        }
        return -1;
    }

}
