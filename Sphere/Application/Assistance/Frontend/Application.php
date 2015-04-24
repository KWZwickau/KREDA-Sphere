<?php
namespace KREDA\Sphere\Application\Assistance\Frontend;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TagIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Info;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Common\AbstractFrontend;

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
            .new Info( 'Dieser Bereich der Anwendung wird eventuell gerade gewartet' )
            .new Warning( 'Die Anwendung kann wegen Kapazitätsproblemen im Moment nicht verwendet werden' )
            .new Danger( 'Die Anwendung hat erkannt, dass das System nicht fehlerfrei arbeiten kann' )
            .new Danger( 'Die interne Kommunikation der Anwendung mit weiteren, notwendigen Resourcen zum Beispiel Datenbanken kann gestört sein' )
            .'<h2 class="text-left" ><small > Mögliche Lösungen </small></h2> '
            .new Info( 'Versuchen Sie die Anwendung zu einem späteren Zeitpunkt erneut aufzurufen' )
            .new Success( 'Bitte wenden Sie sich an den Support damit das Problem schnellstmöglich behoben werden kann' )
        );
        $View->addButton( new Primary( 'Support-Ticket', '/Sphere/Assistance/Support/Ticket', null,
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
            .new Info( 'Dieser Bereich der Anwendung wird eventuell gerade gewartet' )
            .new Danger( 'Die Anwendung hat erkannt, dass das System nicht fehlerfrei arbeiten kann' )
            .new Danger( 'Die interne Kommunikation der Anwendung mit weiteren, notwendigen Resourcen zum Beispiel Programmen kann gestört sein' )
            .'<h2 class="text-left" ><small > Mögliche Lösungen </small></h2> '
            .new Info( 'Versuchen Sie die Anwendung zu einem späteren Zeitpunkt erneut aufzurufen' )
            .new Success( 'Bitte wenden Sie sich an den Support damit das Problem schnellstmöglich behoben werden kann' )
        );
        if (self::extensionRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Fatal') {
            $View->addButton(
                new Primary( 'Fehlerbericht senden', '/Sphere/Assistance/Support/Ticket', null,
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
            .new Info( 'Dieser Bereich der Anwendung wird eventuell gerade gewartet' )
            .new Warning( 'Sie haben im Browser manuell eine nicht vorhandene Adresse aufgerufen' )
            .new Danger( 'Sie haben nicht die erforderliche Berechtigung um diese Resourcen verwenden zu können' )
            .new Danger( 'Die interne Kommunikation der Anwendung mit weiteren, notwendigen Resourcen zum Beispiel Webservern kann gestört sein' )
            .'<h2 class="text-left" ><small > Mögliche Lösungen </small></h2> '
            .new Info( 'Versuchen Sie die Aktion zu einem späteren Zeitpunkt erneut auszuführen' )
            .new Warning( 'Vermeiden Sie es die Adresse im Browser manuell zu bearbeiten' )
            .new Success( 'Bitte wenden Sie sich an den Support damit das Problem schnellstmöglich behoben werden kann' )
        );
        if (self::extensionRequest()->getPathInfo() != '/Sphere/Assistance/Support/Application/Missing') {
            $View->addButton(
                new Primary( 'Support-Ticket', '/Sphere/Assistance/Support/Ticket', null,
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
            .new Info( 'Die Anwendung wird gerade auf den neuesten Stand gebracht' )
            .'<h2 class="text-left" ><small > Mögliche Lösungen </small></h2> '
            .new Success( 'Versuchen Sie die Anwendung zu einem späteren Zeitpunkt erneut aufzurufen' )
        );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageSignature()
    {

        $View = new Stage();
        $View->setTitle( 'Sicherheit' );
        $View->setDescription( 'Parameter' );
        $View->setMessage( '<strong>Problem:</strong> Die Anwendung darf die Anfrage nicht verarbeiten' );
        $View->setContent(
            '<h2 class="text-left"><small>Mögliche Ursachen</small></h2>'
            .new Danger( 'Das System hat fehlerhafte oder mutwillig veränderte Eingabedaten erkannt',
                new WarningIcon() )

            .new Warning( 'Bitte ändern Sie keine Daten in der Url und verwenden Sie nur die vom System erzeugten Anfragen',
                new TagIcon()
            )
            .new Info( 'Bitte führen Sie Anfragen an das System nicht über Tagesgrenzen hinweg aus',
                new TagIcon()
            )
            .new Success( 'Alle Parameter wurden aus Sicherheitsgründen ignoriert',
                new OkIcon()
            )
        );
        return $View;
    }
}
