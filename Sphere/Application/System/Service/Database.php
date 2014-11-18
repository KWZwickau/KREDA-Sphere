<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\Service;
use KREDA\Sphere\Application\System\Service\Database\Status;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;

/**
 * Class Database
 *
 * @package KREDA\Sphere\Application\System\Service
 */
class Database extends Service
{

    const STATUS_MISSING = 0;
    const STATUS_ERROR = 1;
    const STATUS_FAIL = 2;
    const STATUS_OK = 3;
    private $ServiceList = array( 'Access', 'Consumer' );

    /**
     * @return Landing
     */
    public function apiStatus()
    {

        $View = new Landing();
        $View->setTitle( 'Datenbanken' );
        $View->setMessage( '' );

        $Report = array();

        foreach ((array)$this->ServiceList as $Index => $Service) {
            $Config = __DIR__.'/Database/Config/'.$Service.'.ini';
            if (false !== ( $Config = realpath( $Config ) )) {
                $Setting = parse_ini_file( $Config, true );
                if (empty( $Setting )) {
                    $Report[$Index][$Service]['Database/Config/'.$Service.'.ini']['-NA-'] = '<div class="badge badge-warning">Konfiguration fehlerhaft</div>';
                } else {

                    foreach ((array)$Setting as $Key => $Group) {
                        $Key = explode( ':', $Key );
                        try {
                            $this->connectDatabase( $Service, $Key[1] );
                            $Report[$Index][$Service][$Group['Host'].'<br/>'.$Key[0].', '.$Key[1].'</div>'][$Group['Database']] = '<div class="badge badge-success">Verbindung erfolgreich</div>';
                        } catch( \Exception $E ) {
                            $Report[$Index][$Service][$Group['Host'].'<br/>'.$Key[0].', '.$Key[1].'</div>'][$Group['Database']] = '<div class="badge badge-danger">Nicht verbunden</div>';
                        }
                    }
                }
            } else {
                $Report[$Index][$Service]['Database/Config/'.$Service.'.ini']['-NA-'] = '<div class="badge badge-primary">Konfiguration fehlt</div>';
            }
        }

        $Report = new Status( $Report );
        $Report->setRouteUpdate( $this->useRoute( 'Update' ) );

        $View->setContent( $Report->getContent() );

        return $View;
    }
}
