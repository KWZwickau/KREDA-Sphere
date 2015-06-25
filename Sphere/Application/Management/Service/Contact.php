<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Company\Entity\TblCompany;
use KREDA\Sphere\Application\Management\Service\Contact\Entity\TblContact;
use KREDA\Sphere\Application\Management\Service\Contact\Entity\TblMail;
use KREDA\Sphere\Application\Management\Service\Contact\Entity\TblPhone;
use KREDA\Sphere\Application\Management\Service\Contact\EntityAction;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
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
        $this->actionCreateContact('Privat', 'Das ist ein privater Kontakt');
        $this->actionCreateContact('Geschäftlich', 'Das ist ein geschäftlicher Kontakt');
        $this->actionCreateContact('Notfall', 'Das ist ein Notfall Kontakt');
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
     * @param AbstractType $Form
     * @param TblCompany $tblCompany
     * @param $Phone
     *
     * @return AbstractType|string
     */
    public function executeCreateCompanyPhone(
        AbstractType &$Form,
        TblCompany $tblCompany,
        $Phone
    )
    {
        if (null === $Phone
        ) {
            return $Form;
        }
        $Error = false;

        if (isset( $Phone['Number'] ) && empty(  $Phone['Number'] )) {
            $Form->setError( 'Number[Price]', 'Bitte geben Sie einen Preis an' );
            $Error = true;
        }

        if (!$Error) {

            if ( $this->actionCreateCompanyPhone(
                $tblCompany,
                Management::serviceContact()->entityContactById($Phone['Contact']),
                $Phone['Number'],
                $Phone['Description']
            ))
            {
                return new Success( 'Der Kontakt wurde erfolgreich hinzugefügt' )
                .new Redirect( '/Sphere/Management/Company/Phone/Edit', 0, array( 'Id' => $tblCompany->getId()) );
            }
            else
            {
                return new Warning( 'Der Kontakt konnte nicht hinzugefügt werden' )
                .new Redirect( '/Sphere/Management/Company/Phone/Edit', 2, array( 'Id' => $tblCompany->getId()) );
            }
        }

        return $Form;
    }
}
