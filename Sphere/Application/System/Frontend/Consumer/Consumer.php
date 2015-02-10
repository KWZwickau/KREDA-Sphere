<?php
namespace KREDA\Sphere\Application\System\Frontend\Consumer;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
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
     * @return Stage
     */
    public static function stageSummary()
    {

        $View = new Stage();
        $View->setTitle( 'Mandanten' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageConsumerCreate()
    {

        $View = new Stage();
        $View->setTitle( 'Mandanten' );
        $View->setDescription( 'Hinzufügen' );

        $ConsumerList = Gatekeeper::serviceConsumer()->entityConsumerAll();
        $View->setContent(
            new TableData( $ConsumerList, new GridTableTitle( 'Bestehende Mandanten' ), array(
                'Id'                        => 'Id',
                'Name'                      => 'Mandanten-Name',
                'DatabaseSuffix'            => 'Datenbank-Kürzel'
            ) )
            .
            new FormDefault(
                new GridFormGroup(
                    new GridFormRow( array(
                        new GridFormCol(
                            new InputText(
                                'ConsumerName', 'Name des Mandanten', 'Name des Mandanten'
                            )
                        , 6),
                        new GridFormCol(
                            new InputText(
                                'ConsumerSuffix', 'Kürzel des Mandanten', 'Kürzel des Mandanten'
                            )
                        , 6)
                    ) ), new GridFormTitle( 'Mandant anlegen' ) )
            , new ButtonSubmitPrimary( 'Hinzufügen' ) )
        );
        return $View;
    }
}
