<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountType;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Wire;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DisableIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonKeyIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuestionIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RepeatIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\PasswordField;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Account
 *
 * @package KREDA\Sphere\Application\Management\Frontend
 */
class Account extends AbstractFrontend
{

    /**
     * @param int   $Id
     * @param array $Account
     *
     * @return Stage
     */
    public static function stageEdit( $Id, $Account )
    {

        $View = new Stage();
        $View->setTitle( 'Benutzerkonto' );
        $View->setDescription( 'Bearbeiten' );

        $tblAccount = Gatekeeper::serviceAccount()->entityAccountById( $Id );

        $Global = self::extensionSuperGlobal();
        if (!$Global->POST) {
            $Global->POST['Account']['Type'] = $tblAccount->getTblAccountType()->getId();
            $Global->POST['Account']['Token'] = ( $tblAccount->getServiceGatekeeperToken()
                ? $tblAccount->getServiceGatekeeperToken()->getId()
                : 0
            );
            $Global->POST['Account']['Role'] = $tblAccount->getTblAccountRole()->getId();
            $Global->savePost();
        }

        // Filter: No "System"-Accounts !
        $tblAccountType = Gatekeeper::serviceAccount()->entityAccountTypeAll();
        array_walk( $tblAccountType, function ( TblAccountType &$tblAccountType ) {

            if (
                $tblAccountType->getId() == Gatekeeper::serviceAccount()->entityAccountTypeByName( 'System' )->getId()
            ) {
                $tblAccountType = false;
            }
        } );
        $tblAccountType = array_filter( $tblAccountType );
        // Filter: No "System"-Accounts !
        $tblAccountRole = Gatekeeper::serviceAccount()->entityAccountRoleAll();
        array_walk( $tblAccountRole, function ( TblAccountRole &$tblAccountRole ) {

            /**
             * Filter: No "System"-Accounts !
             */
            if (
                $tblAccountRole->getId() == Gatekeeper::serviceAccount()->entityAccountRoleByName( 'System' )->getId()
            ) {
                $tblAccountRole = false;
            }
        } );
        $tblAccountRole = array_filter( $tblAccountRole );

        $tblToken = Gatekeeper::serviceToken()->entityTokenAllByConsumer(
            Gatekeeper::serviceConsumer()->entityConsumerBySession()
        );
        array_unshift( $tblToken, new TblToken( null ) );

        $View->setContent(
            new Layout( new LayoutGroup( array(
                new LayoutRow(
                    new LayoutColumn( array(
                            new Success( $tblAccount->getUsername() ),
                        )
                    ) ),
                new LayoutRow(
                    new LayoutColumn( array(
                        new LayoutTitle( 'Zugangsdaten' ),
                        new Warning( 'Passwortfelder können leer bleiben. Das Passwort wird dann nicht geändert.' ),
                        Gatekeeper::serviceAccount()->executeChangeAccount(
                            new Form( array(
                                new FormGroup( array(
                                    new FormRow( array(
                                        new FormColumn(
                                            new PasswordField( 'Account[Password]', 'Passwort', 'Passwort',
                                                new LockIcon()
                                            ), 4 ),
                                        new FormColumn(
                                            new PasswordField( 'Account[PasswordSafety]',
                                                'Passwort wiederholen',
                                                'Passwort wiederholen',
                                                new RepeatIcon()
                                            ), 4 ),
                                        new FormColumn(
                                            new SelectBox( 'Account[Token]', 'Hardware Schlüssel',
                                                array( 'Serial' => $tblToken ), new PersonKeyIcon()
                                            ), 4 ),
                                    ) ),
                                ) ),
                                new FormGroup( array(
                                    new FormRow( array(
                                        new FormColumn(
                                            new SelectBox( 'Account[Type]', 'Authentifizierungstyp',
                                                array( 'Name' => $tblAccountType ), new PersonKeyIcon()
                                            ), 4 ),
                                        new FormColumn(
                                            new SelectBox( 'Account[Role]', 'Berechtigungsstufe',
                                                array( 'Name' => $tblAccountRole ), new PersonKeyIcon()
                                            ), 4 ),
                                    ) ),
                                ), new FormTitle( 'Berechtigungen' ) )
                            ), new SubmitPrimary( 'Änderungen speichern' )
                            ), $tblAccount, $Account )
                    ) )
                )
            ) ) )
        );
        return $View;
    }

    /**
     * @param int  $Id
     * @param bool $Confirm
     *
     * @return Stage
     */
    public static function stageDestroy( $Id, $Confirm = false )
    {

        $View = new Stage();
        $View->setTitle( 'Benutzerkonto' );
        $View->setDescription( 'Löschen' );

        $tblAccount = Gatekeeper::serviceAccount()->entityAccountById( $Id );
        if (!$Confirm) {
            $View->setContent(
                new Layout(
                    new LayoutGroup( array(
                        new LayoutRow(
                            new LayoutColumn(
                                new Warning(
                                    'Wollen Sie den Benutzer ['.$tblAccount->getUsername().'] wirklich löschen?',
                                    new QuestionIcon()
                                )
                            )
                        ),
                        new LayoutRow(
                            new LayoutColumn( array(
                                new Danger(
                                    'Ja', '/Sphere/Management/Account/Destroy', new OkIcon(),
                                    array( 'Id' => $Id, 'Confirm' => true )
                                ),
                                new Primary(
                                    'Nein', '/Sphere/Management/Account', new DisableIcon()
                                )
                            ) )
                        )
                    ) )
                )
            );
        } else {
            $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerBySession();
            $tblAccount = Gatekeeper::serviceAccount()->entityAccountById( $Id );
            if ($tblAccount && $tblAccount->getServiceGatekeeperConsumer() && $tblAccount->getServiceGatekeeperConsumer()->getId() == $tblConsumer->getId()) {
                if (true !== ( $Wire = Gatekeeper::serviceAccount()->executeDestroyAccount( $tblAccount ) )) {
                    return new Wire( $Wire );
                }
            }
            $View->setContent(
                new Layout( new LayoutGroup( array(
                    new LayoutRow(
                        new LayoutColumn(
                            new Success(
                                'Der Benutzer ['.$tblAccount->getUsername().'] wurde gelöscht'
                            )
                        )
                    ),
                    new LayoutRow(
                        new LayoutColumn( array(
                            new Redirect( '/Sphere/Management/Account' )
                        ) )
                    )
                ) ) ) );
        }
        return $View;
    }

}
