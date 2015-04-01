<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\YubiKeyIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitDanger;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle as FormTitle;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\HiddenField;
use KREDA\Sphere\Client\Frontend\Input\Type\PasswordField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutRight;
use KREDA\Sphere\Client\Frontend\Message\Type\Info;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;

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
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;

        if (Gatekeeper::serviceAccess()->checkIsValidAccess( '/Sphere/Management/Token' )) {
            self::registerClientRoute( self::$Configuration,
                '/Sphere/Management/Token', __CLASS__.'::frontendToken'
            )
                ->setParameterDefault( 'CredentialKey', null )
                ->setParameterDefault( 'Id', null );
        }
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
                $Id = new HiddenField( 'Id' );
                $Id->setDefaultValue( $T->getId(), true );
                /** @noinspection PhpUndefinedFieldInspection */
                $T->AccountList =
                    new Info( 'Keine Daten verfügbar' )
                    .new LayoutRight( new Form(
                        new FormGroup(
                            new FormRow(
                                new FormColumn( array( $Id, new SubmitDanger( 'Schlüssel löschen' ) ) )
                            )
                        )
                    ) );
            } else {
                array_walk( $tblAccountList, function ( TblAccount &$A ) {

                    $tblPerson = $A->getServiceManagementPerson();

                    $A = array(
                        'Konto'  => $A->getUsername(),
                        'Person' =>
                            ( empty( $tblPerson ) ? new Warning( 'Keine Daten verfügbar' ) : $tblPerson->getFullName() )
                    );
                } );
                /** @noinspection PhpUndefinedFieldInspection */
                $T->AccountList = new TableData( $tblAccountList, null, array(), false );
            }
        } );

        $View->setContent(
            new LayoutTitle( 'Bestehende Schlüssel', 'YubiKey' )
            .
            ( empty( $tblTokenList )
                ? new Warning( 'Keine Schlüssel verfügbar' )
                : new TableData( $tblTokenList, null, array(
                    'Id'          => 'Schlüssel-Id',
                    'Serial'      => 'Serien-Nummer',
                    'Identifier'  => 'Schlüssel-Nummer',
                    'AccountList' => 'verknüpfte Benutzerkonten'
                ) )
            )
            .
            Gatekeeper::serviceToken()->executeCreateToken(
                new Form(
                    new FormGroup(
                        new FormRow(
                            new FormColumn(
                                new PasswordField( 'CredentialKey', 'YubiKey', 'YubiKey', new YubiKeyIcon() )
                            )
                        ), new FormTitle( 'Schlüssel hinzufügen', 'YubiKey' ) )
                ), $CredentialKey, $tblConsumer
            )
        );

        return $View;
    }

}
