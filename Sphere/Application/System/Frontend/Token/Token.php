<?php
namespace KREDA\Sphere\Application\System\Frontend\Token;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\PasswordField;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableTitle;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;

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
                new TableTitle( 'Zertifizierte Hardware-Schlüssel', 'YubiKey' ),
                array(
                    'Id'      => 'Id',
                    'Serial'  => 'Seriennummer',
                    'Account' => 'Verwendet durch'
                )
            )
            .new Form(
                new FormGroup(
                    new FormRow(
                        new FormColumn(
                            new PasswordField(
                                'CredentialKey', 'YubiKey', 'YubiKey'
                            )
                        )
                    ), new FormTitle( 'Hardware-Schlüssel hinzufügen', 'YubiKey' ) ),
                new SubmitPrimary( 'Hinzufügen' )
            )
        );
        return $View;
    }
}
