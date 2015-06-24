<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Service\Company\Entity\TblCompany;
use KREDA\Sphere\Application\Management\Service\Contact\Entity\TblContact;
use KREDA\Sphere\Application\Management\Service\Contact\Entity\TblMail;
use KREDA\Sphere\Application\Management\Service\Contact\Entity\TblPhone;
use KREDA\Sphere\Application\Management\Service\Contact\EntityAction;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Contact
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Contact extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     *
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Management', 'Contact', $this->getConsumerSuffix() );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblContact
     */
    public function entityContactById( $Id )
    {

        return parent::entityContactById( $Id );
    }

    /**
     * @return bool|Contact\Entity\TblContact[]
     */
    public function entityContactAll()
    {
        return parent::entityContactAll();
    }

    /**
     * @param int $Id
     *
     * @return bool|TblPhone
     */
    public function entityPhoneById($Id)
    {
        return parent::entityPhoneById($Id); 
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return bool|Contact\Entity\TblPhone[]
     */
    public function entityPhoneAllByPerson(TblPerson $tblPerson)
    {
        return parent::entityPhoneAllByPerson($tblPerson); 
    }

    /**
     * @param TblCompany $tblCompany
     *
     * @return bool|Contact\Entity\TblPhone[]
     */
    public function entityPhoneAllByCompany(TblCompany $tblCompany)
    {
        return parent::entityPhoneAllByCompany($tblCompany); 
    }

    /**
     * @param int $Id
     *
     * @return bool|TblMail
     */
    public function entityMailById($Id)
    {
        return parent::entityMailById($Id); 
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return bool|Contact\Entity\TblMail[]
     */
    public function entityMailAllByPerson(TblPerson $tblPerson)
    {
        return parent::entityMailAllByPerson($tblPerson); 
    }

    /**
     * @param TblCompany $tblCompany
     *
     * @return bool|Contact\Entity\TblMail[]
     */
    public function entityMailAllByCompany(TblCompany $tblCompany)
    {
        return parent::entityMailAllByCompany($tblCompany); 
    }

    /**
     * @param $Name
     * @param $Description
     *
     * @return TblContact|null
     */
    public function actionCreateContact(
        $Name,
        $Description
    )
    {
        return parent::actionCreateContact($Name, $Description); 
    }

    /**
     * @param TblPerson $tblPerson
     * @param TblContact $tblContact
     * @param $Address
     * @param $Description
     *
     * @return TblMail|null
     */
    public function actionCreatePersonMail(
        TblPerson $tblPerson,
        TblContact $tblContact,
        $Address,
        $Description
    )
    {
        return parent::actionCreatePersonMail($tblPerson, $tblContact, $Address, $Description); 
    }

    /**
     * @param TblPerson $tblPerson
     * @param TblContact $tblContact
     * @param $Number
     * @param $Description
     *
     * @return TblPhone|null
     */
    public function actionCreatePersonPhone(
        TblPerson $tblPerson,
        TblContact $tblContact,
        $Number,
        $Description
    )
    {
        return parent::actionCreatePersonPhone($tblPerson, $tblContact, $Number, $Description); 
    }

    /**
     * @param TblCompany $tblCompany
     * @param TblContact $tblContact
     * @param $Number
     * @param $Description
     *
     * @return TblPhone|null
     */
    public function actionCreateCompanyPhone(
        TblCompany $tblCompany,
        TblContact $tblContact,
        $Number,
        $Description
    )
    {
        return parent::actionCreateCompanyPhone($tblCompany, $tblContact, $Number, $Description); 
    }

    /**
     * @param TblCompany $tblCompany
     * @param TblContact $tblContact
     * @param $Address
     * @param $Description
     *
     * @return TblMail|null
     */
    public function actionCreateCompanyMail(
        TblCompany $tblCompany,
        TblContact $tblContact,
        $Address,
        $Description
    )
    {
        return parent::actionCreateCompanyMail($tblCompany, $tblContact, $Address, $Description); 
    }

    /**
     * @param TblMail $tblMail
     *
     * @return bool
     */
    public function actionDestroyMail(
        TblMail $tblMail
    )
    {
        return parent::actionDestroyMail($tblMail); 
    }

    /**
     * @param TblPhone $tblPhone
     *
     * @return bool
     */
    public function actionDestroyPhone(
        TblPhone $tblPhone
    )
    {
        return parent::actionDestroyPhone($tblPhone); 
    }
}
