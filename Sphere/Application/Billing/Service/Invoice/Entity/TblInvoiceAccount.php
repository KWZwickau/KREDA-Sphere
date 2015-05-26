<?php
namespace KREDA\Sphere\Application\Billing\Service\Invoice\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblInvoiceAccount")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblInvoiceAccount extends AbstractEntity
{
    /**
     * @Column(type="bigint")
     */
    protected $tblInvoiceItem;

    /**
     * @Column(type="bigint")
     */
    protected $serviceBilling_Account;

    /**
     * @return bool|TblInvoiceItem
     */
    public function getTblInvoiceItem()
    {
        if (null === $this->tblInvoiceItem) {
            return false;
        } else {
            return Billing::serviceInvoice()->entityInvoiceItemById( $this->tblInvoiceItem );
        }
    }

    /**
     * @param null|TblInvoiceItem $tblInvoiceItem
     */
    public function setTblInvoice( TblInvoiceItem $tblInvoiceItem = null )
    {
        $this->tblInvoiceItem = ( null === $tblInvoiceItem ? null : $tblInvoiceItem->getId() );
    }

    /**
     * @return bool|TblAccount
     */
    public function getServiceBilling_Account()
    {
        if (null === $this->serviceBilling_Account) {
            return false;
        } else {
            return Billing::serviceAccount()->entityAccountById($this->serviceBilling_Account);
        }
    }

    /**
     * @param TblAccount $tblAccount
     */
    public function setServiceBilling_Account( TblAccount $tblAccount = null )
    {
        $this->serviceBilling_Account = ( null === $tblAccount ? null : $tblAccount->getId());
    }
}
