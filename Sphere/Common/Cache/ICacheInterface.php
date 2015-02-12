<?php
namespace KREDA\Sphere\Common\Cache;

/**
 * Interface ICacheInterface
 *
 * @package KREDA\Sphere\Common\Cache
 */
interface ICacheInterface
{

    /**
     * @return void
     */
    public static function clearCache();

    /**
     * @return integer
     */
    public function getCountHits();

    /**
     * @return integer
     */
    public function getCountMisses();

    /**
     * @return integer
     */
    public function getSizeAvailable();

    /**
     * @return integer
     */
    public function getSizeUsed();

    /**
     * @return integer
     */
    public function getSizeFree();

    /**
     * @return integer
     */
    public function getSizeWasted();
}
