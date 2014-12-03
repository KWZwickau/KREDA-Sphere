<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class Property
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Property extends AbstractService
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
