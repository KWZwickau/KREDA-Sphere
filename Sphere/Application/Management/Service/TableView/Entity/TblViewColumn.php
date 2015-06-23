<?php
namespace KREDA\Sphere\Application\Management\Service\TableView\Entity;

use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblViewColumn")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblViewColumn extends AbstractEntity
{

    const ATTR_TBL_VIEW = 'TblView';

    /**
     * @Column(type="string")
     */
    protected $Name;
    /**
     * @Column(type="string")
     */
    protected $DataType;
    /**
     * @Column(type="bigint")
     */
    protected $TblView;

    /**
     * @return string
     */
    public function getName()
    {

        return $this->Name;
    }

    /**
     * @param string $Name
     */
    public function setName( $Name )
    {

        $this->Name = $Name;
    }

    /**
     * @return string
     */
    public function getDataType()
    {

        return $this->DataType;
    }

    /**
     * @param string $DataType
     */
    public function setDataType( $DataType )
    {

        $this->DataType = $DataType;
    }

//    /**
//     * @return bool|TblView
//     */
//    public function getTblView()
//    {
//
//        if (null === $this->TblView) {
//            return false;
//        } else {
//            return Management::serviceTableView()->entityPersonById( $this->serviceManagement_Person );
//        }
//    }
//
//    /**
//     * @param null|TblPerson $tblPerson
//     */
//    public function setServiceManagementPerson( TblPerson $tblPerson = null )
//    {
//
//        $this->serviceManagement_Person = ( null === $tblPerson ? null : $tblPerson->getId() );
//    }

}