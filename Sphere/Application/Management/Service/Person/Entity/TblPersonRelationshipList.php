<?php
namespace KREDA\Sphere\Application\Management\Service\Person\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblPersonRelationshipList")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblPersonRelationshipList extends AbstractEntity
{

    const ATTR_TBL_PERSON_RELATIONSHIP_TYPE = 'tblPersonRelationshipType';
    const ATTR_TBL_PERSON_A = 'tblPersonA';
    const ATTR_TBL_PERSON_B = 'tblPersonB';
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;

    /**
     * @Column(type="bigint")
     */
    protected $tblPersonRelationshipType;
    /**
     * @Column(type="bigint")
     */
    protected $tblPersonA;
    /**
     * @Column(type="bigint")
     */
    protected $tblPersonB;
}
