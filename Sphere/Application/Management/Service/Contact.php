<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Service\Contact\Entity\TblContact;
use KREDA\Sphere\Application\Management\Service\Contact\EntityAction;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Contact
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Contact extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     *
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Management', 'Contact', $this->getConsumerSuffix() );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblContact
     */
    public function entityContactById( $Id )
    {

        return parent::entityContactById( $Id );
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return bool|TblContact[]
     */
    public function entityContactAllByPerson( TblPerson $tblPerson )
    {

        return parent::entityContactAllByPerson( $tblPerson );
    }

}
