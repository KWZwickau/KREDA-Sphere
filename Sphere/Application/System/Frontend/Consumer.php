<?php
namespace KREDA\Sphere\Application\System\Frontend;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableTitle;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;

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
            new TableData( $ConsumerList, new TableTitle( 'Bestehende Mandanten' ), array(
                'Id'             => 'Id',
                'Name'           => 'Mandanten-Name',
                'DatabaseSuffix' => 'Datenbank-Kürzel'
            ) )
            .Gatekeeper::serviceConsumer()->executeCreateConsumer(
                new Form(
                    new FormGroup(
                        new FormRow( array(
                            new FormColumn(
                                new TextField(
                                    'ConsumerName', 'Name des Mandanten', 'Name des Mandanten'
                                )
                                , 6 ),
                            new FormColumn(
                                new TextField(
                                    'ConsumerSuffix', 'Kürzel des Mandanten', 'Kürzel des Mandanten'
                                )
                                , 6 )
                        ) ), new FormTitle( 'Mandant anlegen' ) )
                    , new SubmitPrimary( 'Hinzufügen' ) )
                , $ConsumerSuffix, $ConsumerName )
        );
        return $View;
    }
}
