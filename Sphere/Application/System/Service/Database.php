<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\System\Service\Database\Status;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class Database
 *
 * @package KREDA\Sphere\Application\System\Service
 */
class Database extends AbstractService
{

    const STATUS_MISSING = 0;
    const STATUS_ERROR = 1;
    const STATUS_FAIL = 2;
    const STATUS_OK = 3;
    /** @var array $ServiceList */
    private $ServiceList = array(
        'Gatekeeper' => array(
            'Access'   => array( '' ),
            'Account'  => array( '' ),
            'Consumer' => array( '' ),
            'Token'    => array( '' ),
        ),
        'Management' => array(
            'Person'    => array( 'Annaberg' ),
            'Address'   => array( 'Annaberg' ),
            'Education' => array( 'Annaberg' ),
        )
    );

    /**
     * @return Landing
     */
    public function guiDatabaseStatus()
    {

        $View = new Stage();
        $View->setTitle( 'Datenbank-Cluster' );
        $View->setDescription( 'Status' );
        $View->setMessage( 'Zeigt die aktuelle Konfiguration und den Verbindungsstatus' );

        $Report = array();

        ksort( $this->ServiceList );
        foreach ((array)$this->ServiceList as $Application => $ServiceList) {
            $Config = __DIR__.'/../Database/Config/'.$Application.'.ini';
            if (false !== ( $Config = realpath( $Config ) )) {
                ksort( $ServiceList );
                foreach ((array)$ServiceList as $Service => $ConsumerList) {
                    ksort( $ConsumerList );
                    foreach ((array)$ConsumerList as $Consumer) {
                        $Setting = parse_ini_file( $Config, true );
                        if (isset( $Setting[$Service.':'.$Consumer] )) {

                            $Group = $Setting[$Service.':'.$Consumer];
                            try {
                                $this->setDatabaseHandler( $Application, $Service, $Consumer );
                                $Report[$Application][$Application][''.$Service.'<br/>'.$Consumer.''][$Group['Host'].'<br/>'.$Group['Database'].( empty( $Consumer ) ? '' : '_'.$Consumer )] = '<div class="badge badge-success">Verbindung erfolgreich</div>';
                            } catch( \Exception $E ) {
                                $Report[$Application][$Application][''.$Service.'<br/>'.$Consumer.''][$Group['Host'].'<br/>'.$Group['Database'].( empty( $Consumer ) ? '' : '_'.$Consumer )] = '<div class="badge badge-danger">Nicht verbunden</div>';
                            }
                        } else {
                            $Report[$Application][$Application][''.$Service.'<br/>'.$Consumer.'']['-NA-'] = '<div class="badge badge-danger">Konfiguration fehlerhaft</div>';
                        }
                    }
                }
            } else {
                $Report[$Application][$Application]['System/Database/Config/'.$Application.'.ini']['-NA-'] = '<div class="badge badge-primary">Konfiguration fehlt</div>';
            }
        }

        $Report = new Status( $Report );
        $Report->setRouteUpdate( $this->getClientServiceRoute( 'Update' ) );

        $View->setContent( $Report->getContent() );

        return $View;
    }
}
