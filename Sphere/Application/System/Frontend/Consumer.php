<?php
namespace KREDA\Sphere\Application\System\Frontend;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

/**
 * Class Update
 *
 * @package KREDA\Sphere\Application\System\Consumer
 */
class Consumer extends AbstractFrontend
{

    /**
     * @param string $ConsumerSuffix
     * @param string $ConsumerName
     *
     * @return Stage
     */
    public static function stageCreate( $ConsumerSuffix, $ConsumerName )
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Mandanten' );
        $View->setDescription( 'Hinzufügen' );

        $ConsumerList = Gatekeeper::serviceConsumer()->entityConsumerAll();
        $View->setContent(
            new TableData( $ConsumerList, new GridTableTitle( 'Bestehende Mandanten' ), array(
                'Id'             => 'Id',
                'Name'           => 'Mandanten-Name',
                'DatabaseSuffix' => 'Datenbank-Kürzel'
            ) )
            .Gatekeeper::serviceConsumer()->executeCreateConsumer(
                new FormDefault(
                    new GridFormGroup(
                        new GridFormRow( array(
                            new GridFormCol(
                                new InputText(
                                    'ConsumerName', 'Name des Mandanten', 'Name des Mandanten'
                                )
                                , 6 ),
                            new GridFormCol(
                                new InputText(
                                    'ConsumerSuffix', 'Kürzel des Mandanten', 'Kürzel des Mandanten'
                                )
                                , 6 )
                        ) ), new GridFormTitle( 'Mandant anlegen' ) )
                    , new SubmitPrimary( 'Hinzufügen' ) )
                , $ConsumerSuffix, $ConsumerName )
        );
        return $View;
    }
}
