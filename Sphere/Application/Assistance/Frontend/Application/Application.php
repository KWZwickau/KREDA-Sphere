<?php
namespace KREDA\Sphere\Application\Assistance\Frontend\Application;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageInfo;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageSuccess;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;

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
    static public function stageLaunch()
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
        $View->addButton( '/Sphere/Assistance/Support/Ticket?TicketSubject=Starten der Anwendung'
            .( self::extensionRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Start'
                ? ': '.self::extensionRequest()->getPathInfo()
                : ''
            ),
            'Support-Ticket'
        );
        return $View;
    }

    /**
     * @return Stage
     */
    static public function stageFatal()
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
                trim( '/Sphere/Assistance/Support/Ticket'
                    .'?TicketSubject='.urlencode( 'Fehler in der Anwendung' )
                    .'&TicketMessage='.urlencode( self::extensionRequest()->getPathInfo().': '.$Error['message'].'<br/>'.$Error['file'].':'.$Error['line'] ),
                    '/' )
                , 'Fehlerbericht senden'
            );

        }
        return $View;
    }

    /**
     * @return Stage
     */
    static public function stageMissing()
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
            .new MessageWarning( 'Sie haben im Browser manuell eine nicht vorhandene Addresse aufgerufen' )
            .new MessageDanger( 'Sie haben nicht die erforderliche Berechtigung um diese Resourcen verwenden zu können' )
            .new MessageDanger( 'Die interne Kommunikation der Anwendung mit weiteren, notwendigen Resourcen zum Beispiel Webservern kann gestört sein' )
            .'<h2 class="text-left" ><small > Mögliche Lösungen </small></h2> '
            .new MessageInfo( 'Versuchen Sie die Aktion zu einem späteren Zeitpunkt erneut aufzuführen' )
            .new MessageInfo( 'Vermeiden Sie es die Addresse im Browser manuell zu bearbeiten' )
            .new MessageSuccess( 'Bitte wenden Sie sich an den Support damit das Problem schnellstmöglich behoben werden kann' )
        );
        if (self::extensionRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Missing') {
            $View->addButton( '/Sphere/Assistance/Support/Ticket?TicketSubject=Nicht gefundene Resource'
                .( self::extensionRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Missing'
                    ? ': '.self::extensionRequest()->getPathInfo()
                    : ''
                ),
                'Support-Ticket' );
        }
        return $View;
    }
}
