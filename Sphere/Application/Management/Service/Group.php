<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Service\Group\Action;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Group
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Group extends Action
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     *
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Management', 'Group', $this->getConsumerSuffix() );
    }

    public function setupDatabaseContent()
    {

    }
}
