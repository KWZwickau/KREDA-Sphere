<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Subject
 *
 * @package KREDA\Sphere\Application\Management\Frontend
 */
class Subject extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageSubject()
    {

        $View = new Stage();
        $View->setTitle( 'Fächer' );
        $View->setDescription( '' );
        $View->setMessage( '' );
        $View->setContent( '' );
        return $View;
    }
}
