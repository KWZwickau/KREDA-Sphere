<?php
namespace KREDA\Sphere\Common\Updater\Compression;

use KREDA\Sphere\Common\Updater\Exception\ExtractException;
use KREDA\Sphere\Common\Updater\ICompressionInterface;

/**
 * Class ZipArchive
 *
 * @package KREDA\Sphere\Common\Updater\Archive
 */
class ZipArchive implements ICompressionInterface
{

    private $Location = null;

    /**
     * @param string $Location
     */
    function __construct( $Location )
    {

        $this->Location = $Location;
    }

    /**
     * @param $Location
     *
     * @throws ExtractException
     */
    public function extractTo( $Location )
    {

        $Handler = new \ZipArchive();
        if (true === $Handler->open( $this->Location )) {
            $Detail = $Handler->statIndex( 0 );
            $Target = substr( $Detail['name'], 0, strlen( $Detail['name'] ) - 1 );
            $Handler->extractTo( dirname( $Target ) );
            $Handler->close();
        } else {
            throw new ExtractException();
        }
    }
}
