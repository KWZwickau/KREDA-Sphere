<?php
namespace KREDA\Sphere\Application\Billing\Service\Commodity\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblItemAccount")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblItemAccount extends AbstractEntity
{

    const ATTR_TBL_Item = 'tblItem';
    const ATTR_SERVICE_BILLING_ACCOUNT = 'serviceBilling_Account';

    /**
     * @Column(type="bigint")
     */
    protected $tblItem;

    /**
     * @Column(type="bigint")
     */
    protected $serviceBilling_Account;

    /**
     * @return bool|TblItem
     */
    public function getTblItem()
    {

        if (null === $this->tblItem) {
            return false;
        } else {
            return Billing::serviceCommodity()->entityItemById( $this->tblItem );
        }
    }

    /**
     * @param null|TblItem $tblItem
     */
    public function setTblItem( TblItem $tblItem = null )
    {

        $this->tblItem = ( null === $tblItem ? null : $tblItem->getId() );
    }

    /**
     * @return bool|TblAccount
     */
    public function getServiceBilling_Account()
    {

        if (null === $this->serviceBilling_Account) {
            return false;
        } else {
            return Billing::serviceAccount()->entityAccountById( $this->serviceBilling_Account );
        }
    }

    /**
     * @param TblAccount $serviceBilling_Account
     */
    public function setTblAccount( TblAccount $serviceBilling_Account = null )
    {

        $this->serviceBilling_Account = ( null === $serviceBilling_Account ? null : $serviceBilling_Account->getId() );
    }
}
