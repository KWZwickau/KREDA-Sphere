<?php
namespace KREDA\Sphere\Application\Assistance\Frontend;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageInfo;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageSuccess;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonLinkPrimary;

/**
 * Class Application
 *
 * @package KREDA\Sphere\Application\Assistance\Frontend\Application
 */
class Application extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageWelcome()
    {

        $View = new Stage();
        $View->setTitle( 'Anwendungsfehler' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageLaunch()
    {

        $View = new Stage();
        $View->setTitle( 'Hilfe' );
        $View->setDescription( 'Starten der Anwendung' );
        $View->setMessage( '<strong>Problem:</strong> Nach Aufruf der Anwendung arbeitet diese nicht wie erwartet' );
        $View->setContent(
            ( self::extensionRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Start'
                ? '<div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> <samp>'.self::extensionRequest()->getPathInfo().'</samp></div>'
                : ''
            )
            .( ( $Error = error_get_last() )
                ? '<div class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <samp>'.$Error['message'].'<br/>'.$Error['file'].':'.$Error['line'].'</samp></div>'
                : ''
            )
            .'<h2 class="text-left"><small>Mögliche Ursachen</small></h2>'
            .new MessageInfo( 'Dieser Bereich der Anwendung wird eventuell gerade gewartet' )
            .new MessageWarning( 'Die Anwendung kann wegen Kapazitätsproblemen im Moment nicht verwendet werden' )
            .new MessageDanger( 'Die Anwendung hat erkannt, dass das System nicht fehlerfrei arbeiten kann' )
            .new MessageDanger( 'Die interne Kommunikation der Anwendung mit weiteren, notwendigen Resourcen zum Beispiel Datenbanken kann gestört sein' )
            .'<h2 class="text-left" ><small > Mögliche Lösungen </small></h2> '
            .new MessageInfo( 'Versuchen Sie die Anwendung zu einem späteren Zeitpunkt erneut aufzurufen' )
            .new MessageSuccess( 'Bitte wenden Sie sich an den Support damit das Problem schnellstmöglich behoben werden kann' )
        );
        $View->addButton( new ButtonLinkPrimary( 'Support-Ticket', '/Sphere/Assistance/Support/Ticket', null,
                array(
                    'TicketSubject' => 'Starten der Anwendung'
                        .( self::extensionRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Start'
                            ? ': '.self::extensionRequest()->getPathInfo()
                            : ''
                        ),
                )
            )
        );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageFatal()
    {

        $View = new Stage();
        $View->setTitle( 'Hilfe' );
        $View->setDescription( 'Fehler in der Anwendung' );
        $View->setMessage( '<strong>Problem:</strong> Nach Aufruf der Anwendung arbeitet diese nicht wie erwartet' );
        $View->setContent(
            ( self::extensionRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Fatal'
                ? '<div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> <samp>'.self::extensionRequest()->getPathInfo().'</samp></div>'
                : ''
            )
            .( ( $Error = error_get_last() )
                ? '<div class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <samp>'.$Error['message'].'<br/>'.$Error['file'].':'.$Error['line'].'</samp></div>'
                : ''
            )
            .'<h2 class="text-left"><small>Mögliche Ursachen</small></h2>'
            .new MessageInfo( 'Dieser Bereich der Anwendung wird eventuell gerade gewartet' )
            .new MessageDanger( 'Die Anwendung hat erkannt, dass das System nicht fehlerfrei arbeiten kann' )
            .new MessageDanger( 'Die interne Kommunikation der Anwendung mit weiteren, notwendigen Resourcen zum Beispiel Programmen kann gestört sein' )
            .'<h2 class="text-left" ><small > Mögliche Lösungen </small></h2> '
            .new MessageInfo( 'Versuchen Sie die Anwendung zu einem späteren Zeitpunkt erneut aufzurufen' )
            .new MessageSuccess( 'Bitte wenden Sie sich an den Support damit das Problem schnellstmöglich behoben werden kann' )
        );
        if (self::extensionRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Fatal') {
            $View->addButton(
                new ButtonLinkPrimary( 'Fehlerbericht senden', '/Sphere/Assistance/Support/Ticket', null,
                    array(
                        'TicketSubject' => urlencode( 'Fehler in der Anwendung' ),
                        'TicketMessage' => urlencode( self::extensionRequest()->getPathInfo().': '.$Error['message'].'<br/>'.$Error['file'].':'.$Error['line'] )
                    )
                )
            );

        }
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageMissing()
    {

        $View = new Stage();
        $View->setTitle( 'Hilfe' );
        $View->setDescription( 'Nicht gefundene Resource' );
        $View->setMessage( '<strong>Problem:</strong> Die angegebene Url kann keiner Resource oder Aktion zugewiesen werden, ähnlich einer nicht gefundenen Internetadresse' );
        $View->setContent(
            ( self::extensionRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Missing'
                ? '<div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> <samp>'.self::extensionRequest()->getPathInfo().'</samp></div>'
                : ''
            )
            .'<h2 class="text-left"><small>Mögliche Ursachen</small></h2>'
            .new MessageInfo( 'Dieser Bereich der Anwendung wird eventuell gerade gewartet' )
            .new MessageWarning( 'Sie haben im Browser manuell eine nicht vorhandene Adresse aufgerufen' )
            .new MessageDanger( 'Sie haben nicht die erforderliche Berechtigung um diese Resourcen verwenden zu können' )
            .new MessageDanger( 'Die interne Kommunikation der Anwendung mit weiteren, notwendigen Resourcen zum Beispiel Webservern kann gestört sein' )
            .'<h2 class="text-left" ><small > Mögliche Lösungen </small></h2> '
            .new MessageInfo( 'Versuchen Sie die Aktion zu einem späteren Zeitpunkt erneut auszuführen' )
            .new MessageWarning( 'Vermeiden Sie es die Adresse im Browser manuell zu bearbeiten' )
            .new MessageSuccess( 'Bitte wenden Sie sich an den Support damit das Problem schnellstmöglich behoben werden kann' )
        );
        if (self::extensionRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Missing') {
            $View->addButton(
                new ButtonLinkPrimary( 'Support-Ticket', '/Sphere/Assistance/Support/Ticket', null,
                    array(
                        'TicketSubject' => 'Nicht gefundene Resource'
                            .( self::extensionRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Missing'
                                ? ': '.self::extensionRequest()->getPathInfo()
                                : ''
                            )
                    )
                )
            );
        }
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageUpdate()
    {

        $View = new Stage();
        $View->setTitle( 'Wartungsmodus' );
        $View->setDescription( 'Offline' );
        $View->setMessage( '<strong>Problem:</strong> Die Anwendung kann im Moment nicht verwendet werden' );
        $View->setContent(
            '<h2 class="text-left"><small>Mögliche Ursachen</small></h2>'
            .new MessageInfo( 'Die Anwendung wird gerade auf den neuesten Stand gebracht' )
            .'<h2 class="text-left" ><small > Mögliche Lösungen </small></h2> '
            .new MessageSuccess( 'Versuchen Sie die Anwendung zu einem späteren Zeitpunkt erneut aufzurufen' )
        );
        return $View;
    }
}
