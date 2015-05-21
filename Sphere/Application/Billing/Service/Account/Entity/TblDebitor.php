<?php
namespace KREDA\Sphere\Application\Billing\Service\Account\Entity;

use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblDebitor")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblDebitor extends AbstractEntity
{

//    const ATTR_TBL_PERSON = 'tblPerson';

    /**
     * @Column(type="string")
     */
    protected $DebitorNummer;
    /**
     * @Column(type="integer")
     */
    protected $LeadTimeFirst;
    /**
     * @Column(type="integer")
     */
    protected $LeadTimeFollow;
//    /**
//     * @Column(type="bigint")
//     */
//    protected $PersonId;

    /**
     * @return integer $LeadTimeFirst
     */
    public function getLeadTimeFirst()
    {
        return $this->LeadTimeFirst;
    }

    /**
     * @param integer $leadTimeFirst
     */
    public function setLeadTimeFirst($leadTimeFirst)
    {
        $this->LeadTimeFirst = $leadTimeFirst;
    }

    /**
     * @return integer $LeadTimeFollow
     */
    public function getLeadTimeFollow()
    {
        return $this->LeadTimeFollow;
    }

    /**
     * @param integer $leadTimeFollow
     */
    public function setLeadTimeFollow($leadTimeFollow)
    {
        $this->LeadTimeFollow = $leadTimeFollow;
    }

    /**
     * @return string $DebitorNummer
     */
    public function getDebitorNummer()
    {
        return $this->DebitorNummer;
    }

    /**
     * @param string $debitorNummer
     */
    public function setDebitorNummer($debitorNummer)
    {
        $this->DebitorNummer = $debitorNummer;
    }

}