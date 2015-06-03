<?php
namespace KREDA\Sphere\Application\Billing\Service\Balance;

use KREDA\Sphere\Application\Billing\Service\Balance\Entity\TblBalance;
use KREDA\Sphere\Application\Billing\Service\Balance\Entity\TblPayment;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Billing\Service\Balance
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblBalance
     */
    protected function entityBalanceById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblBalance', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblBalance[]
     */
    protected function entityBalanceAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblBalance' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblPayment
     */
    protected function entityPaymentById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblPayment', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblPayment[]
     */
    protected function entityPaymentAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblPayment' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

}
