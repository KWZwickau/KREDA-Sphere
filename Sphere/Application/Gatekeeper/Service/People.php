<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use KREDA\Sphere\Application\Gatekeeper\Service\People\Search;
use KREDA\Sphere\Application\Service;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use MOC\V\Component\Database\Component\Parameter\Repository\DriverParameter;

class People extends Service
{

    public function __construct()
    {

//        $this->registerDatabaseMaster( 'root', 'kuw', 'ziel2', DriverParameter::DRIVER_PDO_MYSQL, '192.168.100.204' );
    }

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
        $View->setRouteCreate( $this->useRoute( 'Create' ) );
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
        $View->setRouteCreate( $this->useRoute( 'Create' ) );
        return $View;
    }

    public function apiPeopleParent()
    {

        $View = new Search();
        $View->setCategory( 'Eltern' );
        $View->setRouteCreate( $this->useRoute( 'Create' ) );
        return $View;
    }

}
