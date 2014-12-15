<?php
namespace KREDA\Sphere\Application\Assistance\Service;

use KREDA\Sphere\Application\Assistance\Client\Aid\Cause\Danger;
use KREDA\Sphere\Application\Assistance\Client\Aid\Cause\Info;
use KREDA\Sphere\Application\Assistance\Client\Aid\Cause\Warning;
use KREDA\Sphere\Application\Assistance\Client\Aid\Solution\Support;
use KREDA\Sphere\Application\Assistance\Client\Aid\Solution\User;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractService;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class Application
 *
 * @package KREDA\Sphere\Application\Assistance\Service
 */
class Application extends AbstractService
{

    /**
     * @return Stage
     */
    public function apiAidStart()
    {

        $View = new Stage();
        $View->setTitle( 'Hilfe' );
        $View->setDescription( 'Starten der Anwendung' );
        $View->setMessage( '<strong>Problem:</strong> Nach Aufruf der Anwendung arbeitet diese nicht wie erwartet' );
        $View->setContent(
            '<h2 class="text-left"><small>Mögliche Ursachen</small></h2>'
            .new Info( 'Dieser Bereich der Anwendung wird eventuell gerade gewartet' )
            .new Warning( 'Die Anwendung kann wegen Kapazitätsproblemen im Moment nicht verwendet werden' )
            .new Danger( 'Die Anwendung hat erkannt, dass das System nicht fehlerfrei arbeiten kann' )
            .new Danger( 'Die interne Kommunikation der Anwendung mit weiteren, notwendigen Resourcen zum Beispiel Datenbanken kann gestört sein' )
            .'<h2 class="text-left" ><small > Mögliche Lösungen </small></h2> '
            .new User( 'Versuchen Sie die Anwendung zu einem späteren Zeitpunkt erneut aufzurufen' )
            .new Support( 'Bitte wenden Sie sich an den Support damit das Problem schnellstmöglich behoben werden kann' )
        );
        $View->addButton( '/Sphere/Assistance/Support/Ticket?TicketSubject=Starten der Anwendung', 'Support-Ticket' );
        return $View;
    }

    /**
     * @return Stage
     */
    public function apiAidFatal()
    {

        $View = new Stage();
        $View->setTitle( 'Hilfe' );
        $View->setDescription( 'Fehler in der Anwendung' );
        $View->setMessage( '<strong>Problem:</strong> Nach Aufruf der Anwendung arbeitet diese nicht wie erwartet' );
        $View->setContent(
            ( HttpKernel::getRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Fatal'
                ? '<div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> <samp>'.HttpKernel::getRequest()->getPathInfo().'</samp></div>'
                : ''
            )
            .( ( $Error = error_get_last() )
                ? '<div class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <samp>'.$Error['message'].'<br/>'.$Error['file'].':'.$Error['line'].'</samp></div>'
                : ''
            )
            .'<h2 class="text-left"><small>Mögliche Ursachen</small></h2>'
            .new Info( 'Dieser Bereich der Anwendung wird eventuell gerade gewartet' )
            .new Danger( 'Die Anwendung hat erkannt, dass das System nicht fehlerfrei arbeiten kann' )
            .new Danger( 'Die interne Kommunikation der Anwendung mit weiteren, notwendigen Resourcen zum Beispiel Programmen kann gestört sein' )
            .'<h2 class="text-left" ><small > Mögliche Lösungen </small></h2> '
            .new User( 'Versuchen Sie die Anwendung zu einem späteren Zeitpunkt erneut aufzurufen' )
            .new Support( 'Bitte wenden Sie sich an den Support damit das Problem schnellstmöglich behoben werden kann' )
        );
        if (HttpKernel::getRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Fatal') {
            $View->addButton( '/Sphere/Assistance/Support/Ticket?TicketSubject=Fehler in der Anwendung'
                .( HttpKernel::getRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Fatal'
                    ? ': '.urlencode( HttpKernel::getRequest()->getPathInfo().' | '.$Error['message'].'<br/>'.$Error['file'].':'.$Error['line'] )
                    : ''
                ),
                'Support-Ticket' );
        }
        return $View;
    }

    /**
     * @return Stage
     */
    public function apiAidMissingResource()
    {

        $View = new Stage();
        $View->setTitle( 'Hilfe' );
        $View->setDescription( 'Nicht gefundene Resource' );
        $View->setMessage( '<strong>Problem:</strong> Die angegebene Url kann keiner Resource oder Aktion zugewiesen werden, ähnlich einer nicht gefundenen Internetadresse' );
        $View->setContent(
            ( HttpKernel::getRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Missing'
                ? '<div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> <samp>'.HttpKernel::getRequest()->getPathInfo().'</samp></div>'
                : ''
            )
            .'<h2 class="text-left"><small>Mögliche Ursachen</small></h2>'
            .new Info( 'Dieser Bereich der Anwendung wird eventuell gerade gewartet' )
            .new Warning( 'Sie haben im Browser manuell eine nicht vorhandene Addresse aufgerufen' )
            .new Danger( 'Die interne Kommunikation der Anwendung mit weiteren, notwendigen Resourcen zum Beispiel Webservern kann gestört sein' )
            .'<h2 class="text-left" ><small > Mögliche Lösungen </small></h2> '
            .new User( 'Versuchen Sie die Aktion zu einem späteren Zeitpunkt erneut aufzuführen' )
            .new User( 'Vermeiden Sie es die Addresse im Browser manuell zu bearbeiten' )
            .new Support( 'Bitte wenden Sie sich an den Support damit das Problem schnellstmöglich behoben werden kann' )
        );
        if (HttpKernel::getRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Missing') {
            $View->addButton( '/Sphere/Assistance/Support/Ticket?TicketSubject=Nicht gefundene Resource'
                .( HttpKernel::getRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Missing'
                    ? ': '.HttpKernel::getRequest()->getPathInfo()
                    : ''
                ),
                'Support-Ticket' );
        }
        return $View;
    }

}
