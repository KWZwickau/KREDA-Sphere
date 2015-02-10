<?php
namespace KREDA\Sphere;

use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Interface IServiceInterface
 *
 * @package KREDA\Sphere
 */
interface IServiceInterface
{

    /**
     * @param null|TblConsumer $tblConsumer
     *
     * @return static Service Instance
     */
    public static function getApi( TblConsumer $tblConsumer = null );

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDatabaseSchema( $Simulate = true );

    /**
     * @return void
     */
    public function setupDatabaseContent();

    /**
     * @return Handler|null
     */
    public function getDatabaseHandler();

    /**
     * Class MUST OVERWRITE $DatabaseHandler to use this
     *
     * protected static $DatabaseHandler = null;
     *
     * @param string $Application
     * @param string $Service
     * @param string $Consumer
     */
    public function setDatabaseHandler( $Application, $Service = '', $Consumer = '' );

    /**
     * @return string
     */
    public function getConsumerSuffix();
}
