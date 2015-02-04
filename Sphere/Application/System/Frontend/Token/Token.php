<?php
namespace KREDA\Sphere\Application\System\Frontend\Token;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;
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
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

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
        $tblToken = Gatekeeper::serviceToken()->entityTokenAll();
        array_walk( $tblToken, function ( TblToken &$V ) {

            $tblAccountList = Gatekeeper::serviceAccount()->entityAccountAllByToken( $V );
            $V->setIdentifier( strtoupper( $V->getIdentifier() ) );
            if ($V->getSerial() % 2 != 0) {
                $V->setSerial( '0'.$V->getSerial() );
            }
            $V->setSerial( '<span>'.substr( $V->getSerial(), 0, 4 ).' '.substr( $V->getSerial(), 4, 4 ).'</span>' );
            $V = $V->__toArray();
            array_walk( $tblAccountList, function ( TblAccount &$V ) {

                $tblConsumer = $V->getServiceGatekeeperConsumer();
                $tblPerson = $V->getServiceManagementPerson();
                $V = array(
                    '' => '['.$V->getUsername().'] '
                        .( $tblPerson ? $tblPerson->getFirstName().' '.$tblPerson->getLastName() : '' )
                        .( $tblConsumer ? ' ('.$tblConsumer->getDatabaseSuffix().')' : '' )
                );
            } );
            $V['Account'] = new TableData( $tblAccountList, null, array( '' => 'Benutzerkonto' ), false );
            $V['Serial'] .= '&nbsp;&nbsp;<span class="text-muted">-&nbsp;'.$V['Identifier'].'</span>';
            unset( $V['Identifier'] );
        } );
        $View->setContent(
            new TableData(
                $tblToken,
                new GridTableTitle( 'Zertifizierte Hardware-Schlüssel', 'YubiKey' ),
                array(
                    'Id'      => 'Id',
                    'Serial'  => 'Seriennummer',
                    'Account' => 'Verwendet durch'
                )
            )
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
