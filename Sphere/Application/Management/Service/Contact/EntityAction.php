<?php
namespace KREDA\Sphere\Application\Management\Service\Contact;

use KREDA\Sphere\Application\Management\Service\Company\Entity\TblCompany;
use KREDA\Sphere\Application\Management\Service\Contact\Entity\TblContact;
use KREDA\Sphere\Application\Management\Service\Contact\Entity\TblMail;
use KREDA\Sphere\Application\Management\Service\Contact\Entity\TblPhone;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Management\Service\Contact
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblContact
     */
    protected function entityContactById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblContact', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblContact[]
     */
    protected function entityContactAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblContact' )->findAll();
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblPhone
     */
    protected function entityPhoneById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblPhone', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return bool|TblPhone[]
     */
    protected function entityPhoneAllByPerson(TblPerson $tblPerson)
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblPhone' )->findBy(array(
            TblPhone::ATTR_SERVICE_MANAGEMENT_PERSON => $tblPerson->getId()
        ));
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param TblCompany $tblCompany
     *
     * @return bool|TblPhone[]
     */
    protected function entityPhoneAllByCompany(TblCompany $tblCompany)
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblPhone' )->findBy(array(
            TblPhone::ATTR_SERVICE_MANAGEMENT_COMPANY => $tblCompany->getId()
        ));
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblMail
     */
    protected function entityMailById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblMail', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return bool|TblMail[]
     */
    protected function entityMailAllByPerson(TblPerson $tblPerson)
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblMail' )->findBy(array(
            TblMail::ATTR_SERVICE_MANAGEMENT_PERSON => $tblPerson->getId()
        ));
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param TblCompany $tblCompany
     *
     * @return bool|TblMail[]
     */
    protected function entityMailAllByCompany(TblCompany $tblCompany)
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblMail' )->findBy(array(
            TblMail::ATTR_SERVICE_MANAGEMENT_COMPANY => $tblCompany->getId()
        ));
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param $Name
     * @param $Description
     *
     * @return TblContact|null
     */
    protected function actionCreateContact(
        $Name,
        $Description
    ) {

        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'TblContact' )->findOneBy( array(
            TblContact::ATTR_NAME                => $Name,
            TblContact::ATTR_DESCRIPTION               => $Description,
        ) );
        if (null === $Entity) {
            $Entity = new TblContact();
            $Entity->setName( $Name );
            $Entity->setDescription( $Description );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }

        return $Entity;
    }

    /**
     * @param TblPerson $tblPerson
     * @param TblContact $tblContact
     * @param $Number
     * @param $Description
     *
     * @return TblPhone|null
     */
    protected function actionCreatePersonPhone(
        TblPerson $tblPerson,
        TblContact $tblContact,
        $Number,
        $Description
    ) {

        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'TblPhone' )->findOneBy( array(
            TblPhone::ATTR_SERVICE_MANAGEMENT_PERSON                => $tblPerson->getId(),
            TblPhone::ATTR_NUMBER                => $Number,
        ) );
        if (null === $Entity) {
            $Entity = new TblPhone();
            $Entity->setTblContact( $tblContact );
            $Entity->setNumber( $Number );
            $Entity->setDescription( $Description );
            $Entity->setServiceManagementPerson( $tblPerson );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }

        return $Entity;
    }

    /**
     * @param TblCompany $tblCompany
     * @param TblContact $tblContact
     * @param $Number
     * @param $Description
     *
     * @return TblPhone|null
     */
    protected function actionCreateCompanyPhone(
        TblCompany $tblCompany,
        TblContact $tblContact,
        $Number,
        $Description
    ) {

        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'TblPhone' )->findOneBy( array(
            TblPhone::ATTR_SERVICE_MANAGEMENT_COMPANY                => $tblCompany->getId(),
            TblPhone::ATTR_NUMBER                => $Number,
        ) );
        if (null === $Entity) {
            $Entity = new TblPhone();
            $Entity->setTblContact( $tblContact );
            $Entity->setNumber( $Number );
            $Entity->setDescription( $Description );
            $Entity->setServiceManagementCompany( $tblCompany );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }

        return $Entity;
    }

    /**
     * @param TblPerson $tblPerson
     * @param TblContact $tblContact
     * @param $Address
     * @param $Description
     *
     * @return TblMail|null
     */
    protected function actionCreatePersonMail(
        TblPerson $tblPerson,
        TblContact $tblContact,
        $Address,
        $Description
    ) {

        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'TblMail' )->findOneBy( array(
            TblMail::ATTR_SERVICE_MANAGEMENT_PERSON                => $tblPerson->getId(),
            TblMail::ATTR_ADDRESS             => $Address,
        ) );
        if (null === $Entity) {
            $Entity = new TblMail();
            $Entity->setTblContact( $tblContact );
            $Entity->setAddress( $Address );
            $Entity->setDescription( $Description );
            $Entity->setServiceManagementPerson( $tblPerson );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }

        return $Entity;
    }

    /**
     * @param TblCompany $tblCompany
     * @param TblContact $tblContact
     * @param $Address
     * @param $Description
     *
     * @return TblMail|null
     */
    protected function actionCreateCompanyMail(
        TblCompany $tblCompany,
        TblContact $tblContact,
        $Address,
        $Description
    ) {

        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'TblMail' )->findOneBy( array(
            TblMail::ATTR_SERVICE_MANAGEMENT_COMPANY                => $tblCompany->getId(),
            TblMail::ATTR_ADDRESS             => $Address,
        ) );
        if (null === $Entity) {
            $Entity = new TblMail();
            $Entity->setTblContact( $tblContact );
            $Entity->setAddress( $Address );
            $Entity->setDescription( $Description );
            $Entity->setServiceManagementCompany( $tblCompany );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }

        return $Entity;
    }

    /**
     * @param TblPhone $tblPhone
     *
     * @return bool
     */
    protected function actionDestroyPhone(
        TblPhone $tblPhone
    ) {

        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'TblPhone' )->findOneBy( array(
                'Id' => $tblPhone->getId()
        ) );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }

        return false;
    }

    /**
     * @param TblMail $tblMail
     *
     * @return bool
     */
    protected function actionDestroyMail(
        TblMail $tblMail
    ) {

        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'TblMail' )->findOneBy( array(
            'Id' => $tblMail->getId()
        ) );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }

        return false;
    }
}
