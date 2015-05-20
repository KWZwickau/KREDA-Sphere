<?php
namespace KREDA\Sphere\Application\Billing\Service\Account\Entity;

use KREDA\Sphere\Common\AbstractEntity;

class TblDebitor extends AbstractEntity
{

    const ATTR_TBL_PERSON = 'tblPerson';

    /**
     * @Column(type="string")
     */
    protected $DebitorNummer;
    /**
     * @Column(type="date")
     */
    protected $LeadTimeFirst;
    /**
     * @Column(type="date")
     */
    protected $LeadTimeFollow;
    /**
     * @Column(type="bigint")
     */
    protected $PersonId;

    /**
     * @return $LeadTimeFirst
     */
    public function getLeadTimeFirst()
    {
        return $this->LeadTimeFirst;
    }

    /**
     * @param date $leadTimeFirst
     */
    public function setLeadTimeFirst($leadTimeFirst)
    {
        $this->LeadTimeFirst = $leadTimeFirst;
    }

    /**
     * @return $LeadTimeFollow
     */
    public function getLeadTimeFollow()
    {
        return $this->LeadTimeFollow;
    }

    /**
     * @param date $leadTimeFollow
     */
    public function setLeadTimeFollow($leadTimeFollow)
    {
        $this->LeadTimeFollow = $leadTimeFollow;
    }

    /**
     * @return $DebitorNummer
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