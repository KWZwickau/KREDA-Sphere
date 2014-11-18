<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Service;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;

/**
 * Class Property
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Property extends Service
{

    /**
     * @return Landing
     */
    public function apiMain()
    {

        $View = new Landing();
        $View->setTitle( 'Immobilien' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

}
