<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

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
 * Class Right
 *
 * @package KREDA\Sphere\Application\System\Frontend\Authorization
 */
class Right extends AbstractFrontend
{

    /**
     * @param null|string $Name
     *
     * @return Stage
     */
    public static function stageRight( $Name )
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigungen' );
        $View->setDescription( 'Rechte' );
        $View->setContent(
            new TableData( Gatekeeper::serviceAccess()->entityRightAll(),
                new TableTitle( 'Bestehende Rechte', 'Routen' ) )
            .Gatekeeper::serviceAccess()->executeCreateRight(
                new Form(
                    new FormGroup(
                        new FormRow(
                            new FormColumn(
                                new TextField(
                                    'RightName', 'Route', 'Name'
                                )
                            )
                        ), new FormTitle( 'Recht anlegen', 'Route' ) )
                    , new SubmitPrimary( 'Hinzufügen' )
                )
                , $Name )
        );
        return $View;
    }
}
