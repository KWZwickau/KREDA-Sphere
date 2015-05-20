<?php
namespace KREDA\Sphere\Application\Billing\Service\Account;

use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccountType;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Billing\Service\Account
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblAccountType
     */
    protected function entityAccountTypeById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccountType', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    protected function entityAccountKeyById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccountType', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    protected function entityAccountKeyTypeById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccountKeyType', $Id );
        return ( null === $Entity ? false : $Entity);
    }

    protected function entityDebitorById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblDebitor', $Id );
        return (null === $Entity ? false : $Entity);
    }
}
