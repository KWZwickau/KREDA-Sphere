<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\YubiKeyIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageInfo;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitDanger;
use KREDA\Sphere\Common\Frontend\Form\Element\InputHidden;
use KREDA\Sphere\Common\Frontend\Form\Element\InputPassword;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

/**
 * Class Token
 *
 * @package KREDA\Sphere\Application\Management\Module
 */
class Token extends Common
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     *
     * @return Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Token', __CLASS__.'::frontendToken'
        )
            ->setParameterDefault( 'CredentialKey', null )
            ->setParameterDefault( 'Id', null );
    }

    /**
     * @param null|string $CredentialKey
     * @param null|int    $Id
     *
     * @return Stage
     */
    public static function frontendToken( $CredentialKey, $Id )
    {

        self::setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Hardware-Schlüssel' );
        $View->setDescription( 'YubiKey' );

        $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerBySession();

        if (null !== $Id) {
            $tblToken = Gatekeeper::serviceToken()->entityTokenById( $Id );
            if ($tblToken && false !== $tblToken->getServiceGatekeeperConsumer() && $tblToken->getServiceGatekeeperConsumer()->getId() == $tblConsumer->getId()) {
                Gatekeeper::serviceToken()->executeDestroyToken( $tblToken );
            }
        }
        $tblTokenList = Gatekeeper::serviceToken()->entityTokenAllByConsumer( $tblConsumer );

        if (!$tblTokenList) {
            $tblTokenList = array();
        }

        array_walk( $tblTokenList, function ( TblToken &$T ) {

            $T->setSerial( implode( ' ', str_split( str_pad( $T->getSerial(), 8, '0', STR_PAD_LEFT ), 4 ) ) );
            $T->setIdentifier( strtoupper( $T->getIdentifier() ) );

            $tblAccountList = Gatekeeper::serviceAccount()->entityAccountAllByToken( $T );
            if (empty( $tblAccountList )) {
                $Id = new InputHidden( 'Id' );
                $Id->setDefaultValue( $T->getId(), true );
                $T->AccountList =
                    new MessageInfo( 'Keine Daten verfügbar' )
                    .'<div class="pull-right">'.new FormDefault(
                        new GridFormGroup(
                            new GridFormRow(
                                new GridFormCol( array( $Id, new ButtonSubmitDanger( 'Schlüssel löschen' ) ) )
                            )
                        )
                    ).'</div>';
            } else {
                array_walk( $tblAccountList, function ( TblAccount &$A ) {

                    $tblPerson = $A->getServiceManagementPerson();

                    $A = array(
                        'Konto'  => $A->getUsername(),
                        'Person' =>
                            ( empty( $tblPerson ) ? new MessageWarning( 'Keine Daten verfügbar' ) : $tblPerson->getFullName() )
                    );
                } );
                $T->AccountList = new TableData( $tblAccountList, null, array(), false );
            }
        } );

        $View->setContent(
            new GridLayoutTitle( 'Bestehende Schlüssel', 'YubiKey' )
            .
            ( empty( $tblTokenList )
                ? new MessageWarning( 'Keine Schlüssel verfügbar' )
                : new TableData( $tblTokenList, null, array(
                    'Id'          => 'Schlüssel-Id',
                    'Serial'      => 'Serien-Nummer',
                    'Identifier'  => 'Schlüssel-Nummer',
                    'AccountList' => 'verknüpfte Benutzerkonten'
                ) )
            )
            .
            Gatekeeper::serviceToken()->executeCreateToken(
                new FormDefault(
                    new GridFormGroup(
                        new GridFormRow(
                            new GridFormCol(
                                new InputPassword( 'CredentialKey', 'YubiKey', 'YubiKey', new YubiKeyIcon() )
                            )
                        ), new GridFormTitle( 'Schlüssel hinzufügen', 'YubiKey' ) )
                ), $CredentialKey, $tblConsumer
            )
        );

        return $View;
    }

}
