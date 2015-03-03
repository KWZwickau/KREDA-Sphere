<?php
namespace KREDA\Sphere\Common\Updater;

use KREDA\Sphere\Common\Updater\Exception\ExtractException;

/**
 * Interface ICompressionInterface
 *
 * @package KREDA\Sphere\Common\Updater
 */
interface ICompressionInterface
{

    /**
     * @param $Location
     */
    public function __construct( $Location );

    /**
     * @param $Location
     *
     * @throws ExtractException
     */
    public function extractTo( $Location );
}
