<?php
namespace KREDA\Sphere\Application\Graduation\Service;

use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Graduation\Service\Weight\EntityAction;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Weight
 *
 * @package KREDA\Sphere\Application\Graduation\Service
 */
class Weight extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @param TblConsumer $tblConsumer
     */
    function __construct( TblConsumer $tblConsumer = null )
    {

        $this->setDatabaseHandler( 'Graduation', 'Weight', $this->getConsumerSuffix( $tblConsumer ) );
    }

    public function setupDatabaseContent()
    {
    }
}
