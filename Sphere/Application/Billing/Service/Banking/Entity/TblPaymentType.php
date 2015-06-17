<?php
namespace KREDA\Sphere\Application\Billing\Service\Banking\Entity;

use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblPaymentType")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblPaymentType extends AbstractEntity
{

    const ATTR_PAYMENT_TYPE = 'PaymentType';

    /**
     * @Column(type="string")
     */
    protected $PaymentType;

    /**
     * @return string $PaymentType
     */
    public function getPaymentType()
    {
        return $this->PaymentType;
    }

    /**
     * @param string $PaymentType
     */
    public function setPaymentType( $PaymentType )
    {
        $this->PaymentType = $PaymentType;
    }

}