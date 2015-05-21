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
    /**
     * @Column(type="bigint")
     */
    protected $tblItem;

    /**
     * @Column(type="bigint")
     */
    protected $tblAccount;

    /**
     * @return bool|TblItem
     */
    public function getTblItem()
    {

        if (null === $this->tblItem)
        {
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
    public function getTblAccount()
    {
        if (null === $this->tblAccount) {
            return false;
        } else {
            return Billing::serviceAccount()->entityAccountById( $this->tblAccount );
        }
    }

    /**
     * @param TblAccount $tblAccount
     */
    public function setTblAccount( TblAccount $tblAccount = null )
    {
        $this->tblAccount = ( null === $tblAccount ? null : $tblAccount->getId() );
    }
}