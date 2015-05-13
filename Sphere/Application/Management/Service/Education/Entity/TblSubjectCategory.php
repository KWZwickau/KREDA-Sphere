<?php
namespace KREDA\Sphere\Application\Management\Service\Education\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblSubjectCategory")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblSubjectCategory extends AbstractEntity
{

    const ATTR_TBL_SUBJECT = 'tblSubject';
    const ATTR_TBL_CATEGORY = 'tblCategory';

    /**
     * @Column(type="bigint")
     */
    protected $tblSubject;
    /**
     * @Column(type="bigint")
     */
    protected $tblCategory;

    /**
     * @return bool|TblSubject
     */
    public function getTblSubject()
    {

        if (null === $this->tblSubject) {
            return false;
        } else {
            return Management::serviceEducation()->entitySubjectById( $this->tblSubject );
        }
    }

    /**
     * @param null|TblSubject $tblSubject
     */
    public function setTblSubject( TblSubject $tblSubject = null )
    {

        $this->tblSubject = ( null === $tblSubject ? null : $tblSubject->getId() );
    }

    /**
     * @return bool|TblCategory
     */
    public function getTblCategory()
    {

        if (null === $this->tblCategory) {
            return false;
        } else {
            return Management::serviceEducation()->entityCategoryById( $this->tblCategory );
        }
    }

    /**
     * @param null|TblCategory $tblCategory
     */
    public function setTblCategory( TblCategory $tblCategory = null )
    {

        $this->tblCategory = ( null === $tblCategory ? null : $tblCategory->getId() );
    }
}
