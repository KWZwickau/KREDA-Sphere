<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use MOC\V\Component\Database\Component\Parameter\Repository\DriverParameter;
use MOC\V\Component\Database\Database;

class People
{

    /** @var \MOC\V\Component\Database\Component\IBridgeInterface $DatabaseInput */
    private static $DatabaseInput = null;
    /** @var \MOC\V\Component\Database\Component\IBridgeInterface $DatabaseOutput */
    private static $DatabaseOutput = null;

    function __construct()
    {

        self::$DatabaseInput = Database::getDatabase( 'root', 'kuw', 'ziel', DriverParameter::DRIVER_PDO_SQLITE,
            '192.168.100.204' );
        self::$DatabaseOutput = Database::getDatabase( 'root', 'kuw', 'ziel2', DriverParameter::DRIVER_PDO_MYSQL,
            '192.168.100.204' );
    }

    public function apiPeople()
    {

        $View = new Landing();
        $View->setTitle( 'Personen' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function apiPeopleStaff()
    {

        $View = new Landing();
        $View->setTitle( 'Personal' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function apiPeopleStudent()
    {

        $View = new Landing();
        $View->setTitle( 'Schüler' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        $View->setContent(
            self::$DatabaseOutput->prepareStatement( "SELECT * FROM user" )->executeRead()
        );
        return $View;
    }

    public function apiPeopleParent()
    {

        $View = new Landing();
        $View->setTitle( 'Eltern' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

}
