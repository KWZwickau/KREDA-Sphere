<?php
namespace KREDA\Sphere\Common\Cache\Type;

use KREDA\Sphere\Common\Cache\ICacheInterface;

/**
 * Class TwigCache
 *
 * @package KREDA\Sphere\Common\Cache\Type
 */
class TwigCache implements ICacheInterface
{

    private static $TwigCache = '/../../../../Library/MOC-V/Component/Template/Component/Bridge/Repository/TwigTemplate';

    /**
     * @return void
     */
    public static function clearCache()
    {

        $E = new \Twig_Environment( null, array( 'cache' => realpath( __DIR__.self::$TwigCache ) ) );
        $E->clearCacheFiles();
        $E->clearTemplateCache();
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
    public function getSizeWasted()
    {

        return $this->getSizeAvailable() - $this->getSizeFree() - $this->getSizeUsed();
    }

    /**
     * @return integer
     */
    public function getSizeAvailable()
    {

        return ( disk_total_space( __DIR__ ) / 1024 );
    }

    /**
     * @return integer
     */
    public function getSizeFree()
    {

        return ( disk_free_space( __DIR__ ) / 1024 );
    }

    /**
     * @return integer
     */
    public function getSizeUsed()
    {

        $bytestotal = 0;
        $path = realpath( __DIR__.self::$TwigCache );
        if ($path !== false) {
            foreach (new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( $path,
                \FilesystemIterator::SKIP_DOTS ) ) as $object) {
                $bytestotal += $object->getSize();
            }
        }
        return $bytestotal;
    }

}
