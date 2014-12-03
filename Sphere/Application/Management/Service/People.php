<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Service\People\Search;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class People
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service
 */
class People extends AbstractService
{

    /**
     * @return Landing
     */
    public function apiMain()
    {

        $View = new Landing();
        $View->setTitle( 'Personen' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function apiPeopleStaff()
    {

        $View = new Search();
        $View->setCategory( 'Personal' );
        $View->setRouteCreate( $this->getClientServiceRoute( 'Create' ) );
        return $View;
    }

    public function apiPeopleStaffCreate()
    {

        $View = new Landing();
        $View->setTitle( 'Anlegen' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function apiPeopleStudent()
    {

        $View = new Search();
        $View->setCategory( 'Schüler' );
        $View->setRouteCreate( $this->getClientServiceRoute( 'Create' ) );
        return $View;
    }

    public function apiPeopleParent()
    {

        $View = new Search();
        $View->setCategory( 'Eltern' );
        $View->setRouteCreate( $this->getClientServiceRoute( 'Create' ) );
        return $View;
    }

}
