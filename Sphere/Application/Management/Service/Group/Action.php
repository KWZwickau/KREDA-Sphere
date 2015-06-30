<?php
namespace KREDA\Sphere\Application\Management\Service\Group;

use KREDA\Sphere\Application\Management\Service\Group\Action\Destroy;
use KREDA\Sphere\Application\Management\Service\Group\Entity\TblCompanyList;
use KREDA\Sphere\Application\Management\Service\Group\Entity\TblGroup;
use KREDA\Sphere\Application\Management\Service\Group\Entity\TblPersonList;

/**
 * Class Action
 *
 * @package KREDA\Sphere\Application\Management\Service\Group
 */
abstract class Action extends Destroy
{

    /**
     * @param TblGroup $tblGroup
     *
     * @return int
     */
    protected function countPersonAllByGroup( TblGroup $tblGroup )
    {

        return (int)$this->getEntityManager()->getEntity( 'TblPersonList' )->countBy( array(
            TblPersonList::ATTR_TBL_GROUP => $tblGroup->getId()
        ) );
    }

    /**
     * @param TblGroup $tblGroup
     *
     * @return int
     */
    protected function countCompanyAllByGroup( TblGroup $tblGroup )
    {

        return (int)$this->getEntityManager()->getEntity( 'TblCompanyList' )->countBy( array(
            TblCompanyList::ATTR_TBL_GROUP => $tblGroup->getId()
        ) );
    }

}
