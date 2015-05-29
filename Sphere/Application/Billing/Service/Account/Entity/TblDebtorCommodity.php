<?php
namespace KREDA\Sphere\Application\Billing\Service\Account\Entity;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblDebtorCommodity")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblDebtorCommodity extends AbstractEntity
{

    const ATTR_TBL_COMMODITY = 'tblCommodity';
    const ATTR_TBL_DEBTOR = 'tblDebtor';

    /**
     * @Column(type="bigint")
     */
    protected $tblCommodity;
    /**
     * @Column(type="bigint")
     */
    protected $tblDebtor;

    /**
     * @return bool|TblDebtor
     */
    public function getTblDebtor()
    {

        if (null === $this->tblDebtor) {
            return false;
        } else {
            return Billing::serviceAccount()->entityDebtorById( $this->tblDebtor );
        }
    }

    /**
     * @param null|TblDebtor $tblDebtor
     */
    public function setTblDebtor( TblDebtor $tblDebtor)
    {

        $this->tblDebtor = (null === $tblDebtor ? null : $tblDebtor->getId() );
    }

}