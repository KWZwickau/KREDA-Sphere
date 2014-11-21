<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access;

use KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema\TblAccessRight;

/**
 * Class Schema
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Access
 */
class Schema extends Setup
{

    /**
     * @param string $Route
     *
     * @return bool|null
     */
    protected function toolCreateAccessRight( $Route )
    {

        $tblAccessRight = $this->EntityManager->getRepository( __NAMESPACE__.'\Schema\TblAccessRight' )
            ->findOneBy( array( TblAccessRight::ATTR_ROUTE => $Route ) );
        if (null === $tblAccessRight) {
            $tblAccessRight = new TblAccessRight( $Route );
            $this->EntityManager->persist( $tblAccessRight );
            $this->EntityManager->flush();
            return true;
        }
        return null;
    }

}
