<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Service\Invoicing\Entity\TblInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoicing\EntityAction;
use KREDA\Sphere\Client\Frontend\Background\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Client\Frontend\Form\AbstractType as AbstractType;

/**
 * Class Commodity
 *
 * @package KREDA\Sphere\Application\Billing\Service
 */
class Invoicing extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {
        $this->setDatabaseHandler( 'Billing', 'Invoicing', $this->getConsumerSuffix() );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

    }

    /**
     * @param int $Id
     *
     * @return bool|TblInvoice
     */
    public function entityInvoiceById($Id)
    {
        return parent::entityInvoiceById($Id);
    }
}