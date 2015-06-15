<?php
namespace KREDA\Sphere\Application\Management\Service\TableView\Entity;

use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblView")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblView extends AbstractEntity
{
    const ATTR_TYPE_NAME = 'TypeName';
    const ATTR_NAME = 'Name';

    /**
     * @Column(type="string")
     */
    protected $TypeName;
    /**
     * @Column(type="string")
     */
    protected $Name;

    /**
     * @return string
     */
    public function getTypeName()
    {

        return $this->TypeName;
    }

    /**
     * @param string $TypeName
     */
    public function setTypeName( $TypeName )
    {

        $this->TypeName = $TypeName;
    }
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
}