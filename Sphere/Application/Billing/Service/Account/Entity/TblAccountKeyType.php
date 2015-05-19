<?php
namespace KREDA\Sphere\Application\Billing\Service\Account\Entity;

use KREDA\Sphere\Common\AbstractEntity;

class TblAccountKeyType extends AbstractEntity
{

    /**
     * @Column(type="string")
     */
    protected $Name;

    /**
     * @Column(type="string")
     */
    protected $Description;

    /**
     * @return string $name
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->Name = $name;
    }

    /**
     * @return string $description
     */
    public function getDescription()
    {

        return $this->Description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {

        $this->Description = $description;
    }



}