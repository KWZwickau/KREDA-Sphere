<?php
namespace KREDA\Sphere\Application\Management\Service\Contact;

use KREDA\Sphere\Application\Management\Service\Contact\Entity\TblContact;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Management\Service\Contact
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblContact
     */
    protected function entityContactById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblContact', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return bool|TblContact[]
     */
    protected function entityContactAllByPerson( TblPerson $tblPerson )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblContact' )->findBy( array(
            TblContact::ATTR_SERVICE_MANAGEMENT_PERSON => $tblPerson->getId(),
        ) );
        return ( null === $Entity ? false : $Entity );
    }

}
