<?php
namespace KREDA\Sphere\Application\System\Frontend\Consumer;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\System\Frontend\Consumer\Setting\CreateConsumer;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Update
 *
 * @package KREDA\Sphere\Application\System\Consumer
 */
class Consumer extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function guiSummary()
    {

        $View = new Landing();
        $View->setTitle( 'Mandanten' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function guiConsumerCreate()
    {

        $View = new Stage();
        $View->setTitle( 'Mandanten' );
        $View->setDescription( 'Hinzufügen' );
        $View->setMessage( '' );
        $View->setContent( new CreateConsumer( Gatekeeper::serviceConsumer()->entityConsumerAll() ) );
        return $View;
    }
}
