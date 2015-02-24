<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

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
                new GridTableTitle( 'Bestehende Rechte', 'Routen' ) )
            .Gatekeeper::serviceAccess()->executeCreateRight(
                new FormDefault(
                    new GridFormGroup(
                        new GridFormRow(
                            new GridFormCol(
                                new InputText(
                                    'RightName', 'Route', 'Name'
                                )
                            )
                        ), new GridFormTitle( 'Recht anlegen', 'Route' ) )
                    , new ButtonSubmitPrimary( 'Hinzufügen' )
                )
                , $Name )
        );
        return $View;
    }
}
