<?php
namespace KREDA\Sphere\Common\Updater\Component;

use KREDA\Sphere\Common\Updater\Exception\ExtractException;
use KREDA\Sphere\Common\Updater\ICompressionInterface;

/**
 * Class ZipArchive
 *
 * @package KREDA\Sphere\Common\Updater\Component
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
     * @param null|string $Location
     *
     * @return string
     * @throws ExtractException
     */
    public function extractTo( $Location = null )
    {

        if (null === $Location) {
            $Location = dirname( $this->Location );
        }

        $Handler = new \ZipArchive();
        if (true === $Handler->open( $this->Location )) {
            $Detail = $Handler->statIndex( 0 );
            $Target = substr( $Detail['name'], 0, strlen( $Detail['name'] ) - 1 );
            if ($Handler->extractTo( $Location )) {
                $Handler->close();
            } else {
                unlink( $this->Location );
                return 0;
            }
        } else {
            unlink( $this->Location );
            return 0;
        }
        return $Target;
    }
}
