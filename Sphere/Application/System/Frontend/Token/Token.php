<?php
namespace KREDA\Sphere\Application\System\Frontend\Token;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Form\Element\InputPassword;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableFromData;

/**
 * Class SignOut
 *
 * @package KREDA\Sphere\Application\System\Frontend\Token
 */
class Token extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageWelcome()
    {

        $View = new Stage();
        $View->setTitle( 'Hardware-Schlüssel' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    /**
     * @param null|string $CredentialKey
     *
     * @return Stage
     */
    public static function stageCertification( $CredentialKey )
    {

        $View = new Stage();
        $View->setTitle( 'Hardware-Schlüssel' );
        $View->setDescription( 'Zertifizierung' );
        $View->setContent(
            new TableFromData(
                Gatekeeper::serviceToken()->entityTokenAll(),
                new GridTableTitle( 'Zertifizierte Hardware-Schlüssel', 'YubiKey' ) )
            .new FormDefault(
                new GridFormGroup(
                    new GridFormRow(
                        new GridFormCol(
                            new InputPassword(
                                'CredentialKey', 'YubiKey', 'YubiKey'
                            )
                        )
                    ), new GridFormTitle( 'Hardware-Schlüssel hinzufügen', 'YubiKey' ) ),
                new ButtonSubmitPrimary( 'Hinzufügen' )
            )
        );
        return $View;
    }
}
