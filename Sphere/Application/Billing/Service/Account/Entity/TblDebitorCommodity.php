<?php
namespace KREDA\Sphere\Application\Billing\Service\Account\Entity;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Common\AbstractEntity;

class TblDebitorCommodity extends AbstractEntity
{

    const ATTR_TBL_COMMODITY = 'tblCommodity';
    const ATTR_TBL_DEBITOR = 'tblDebitor';

    /**
     * @Column(type="bigint")
     */
    protected $tblCommodity;
    /**
     * @Column(type="bigint")
     */
    protected $tblDebitor;

    /**
     * @return bool|TblDebitor
     */
    public function getTblDebitor()
    {

        if (null === $this->tblDebitor) {
            return false;
        } else {
            return Billing::serviceAccount()->entityDebitorById( $this->tblDebitor );
        }
    }

    /**
     * @param null|TblDebitor $tblDebitor
     */
    public function setTblDebitor( TblDebitor $tblDebitor)
    {

        $this->tblDebitor = (null === $tblDebitor ? null : $tblDebitor->getId() );
    }

}