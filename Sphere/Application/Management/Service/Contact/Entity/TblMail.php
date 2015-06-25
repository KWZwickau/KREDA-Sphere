<?php
namespace KREDA\Sphere\Application\Management\Service\Contact\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Company\Entity\TblCompany;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity()
 * @Table(name="tblMail")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblMail extends AbstractEntity
{

    const ATTR_SERVICE_MANAGEMENT_PERSON = 'serviceManagement_Person';
    const ATTR_SERVICE_MANAGEMENT_COMPANY = 'serviceManagement_Company';
    const ATTR_TBL_CONTACT = '$tblContact';
    const ATTR_ADDRESS = 'Address';

    /**
     * @Column(type="bigint")
     */
    protected $tblContact;

    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Person;

    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Company;

    /**
     * @Column(type="string")
     */
    protected $Address;

    /**
     * @Column(type="string")
     */
    protected $Description;

    /**
     * @Column(type="integer")
     */
    protected $Rank;

    /**
     * @param string $Description
     */
    public function setDescription($Description)
    {
        $this->Description = $Description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * @param string $Address
     */
    public function setAddress($Address)
    {
        $this->Address = $Address;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->Address;
    }

    /**
     * @param integer $Rank
     */
    public function setRank($Rank)
    {
        $this->Rank = $Rank;
    }

    /**
     * @return integer
     */
    public function getRank()
    {
        return $this->Rank;
    }

    /**
     * @return bool|TblPerson
     */
    public function getServiceManagementPerson()
    {

        if (null === $this->serviceManagement_Person) {
            return false;
        } else {
            return Management::servicePerson()->entityPersonById( $this->serviceManagement_Person );
        }
    }

    /**
     * @param null|TblPerson $tblPerson
     */
    public function setServiceManagementPerson( TblPerson $tblPerson = null )
    {

        $this->serviceManagement_Person = ( null === $tblPerson ? null : $tblPerson->getId() );
    }

    /**
     * @return bool|TblCompany
     */
    public function getServiceManagementCompany()
    {

        if (null === $this->serviceManagement_Company) {
            return false;
        } else {
            return Management::serviceCompany()->entityCompanyById( $this->serviceManagement_Company );
        }
    }

    /**
     * @param null|TblCompany $tblCompany
     */
    public function setServiceManagementCompany( TblCompany $tblCompany = null )
    {

        $this->serviceManagement_Company = ( null === $tblCompany ? null : $tblCompany->getId() );
    }

    /**
     * @return bool|TblContact
     */
    public function getTblContact()
    {

        if (null === $this->tblContact) {
            return false;
        } else {
            return Management::serviceContact()->entityContactById( $this->tblContact );
        }
    }

    /**
     * @param null|TblContact $tblContact
     */
    public function setTblContact( TblContact $tblContact = null )
    {

        $this->tblContact = ( null === $tblContact ? null : $tblContact->getId() );
    }
}
