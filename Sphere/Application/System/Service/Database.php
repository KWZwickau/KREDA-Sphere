<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\Service;
use KREDA\Sphere\Application\System\Service\Database\Status;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;

class Database extends Service
{

    const STATUS_MISSING = 0;
    const STATUS_ERROR = 1;
    const STATUS_FAIL = 2;
    const STATUS_OK = 3;
    private $ServiceList = array( 'Access', 'People', 'Property', 'Support', 'Assistance' );

    public function apiMain()
    {

        $View = new Landing();
        $View->setTitle( 'Datenbanken' );
        $View->setMessage( '<div class="text-danger">OBACHT!!</div>' );

        $Report = array();

        foreach ((array)$this->ServiceList as $Index => $Service) {
            $Config = __DIR__.'/Database/Config/'.$Service.'.ini';
            if (false !== ( $Config = realpath( $Config ) )) {
                $Setting = parse_ini_file( $Config, true );
                if (empty( $Setting )) {
                    $Report[$Index][$Service]['Database/Config/'.$Service.'.ini'] = '<div class="badge badge-warning">Konfiguration fehlerhaft</div>';
                } else {

                    foreach ((array)$Setting as $Key => $Group) {
                        $Key = explode( ':', $Key );
                        try {
                            switch (strtoupper( $Key[0] )) {
                                case 'MASTER':
                                    $this->registerDatabaseMaster(
                                        $Group['Username'],
                                        $Group['Password'],
                                        $Group['Database'],
                                        $Group['Driver'],
                                        $Group['Host'],
                                        $Group['Port']
                                    );
                                    break;
                                case 'SLAVE':
                                    $this->registerDatabaseSlave(
                                        $Group['Username'],
                                        $Group['Password'],
                                        $Group['Database'],
                                        $Group['Driver'],
                                        $Group['Host'],
                                        $Group['Port']
                                    );
                                    break;
                            }
                            $Report[$Index][$Group['Host'].'<div class="text-info small">'.$Service.' - '.$Key[0].', '.$Key[1].'</div>'][$Group['Database']] = '<div class="badge badge-success">Verbindung erfolgreich</div>';
                        } catch( \Exception $E ) {
                            $Report[$Index][$Group['Host'].'<div class="text-info small">'.$Service.' - '.$Key[0].', '.$Key[1].'</div>'][$Group['Database']] = '<div class="badge badge-danger">Nicht verbunden</div>';
                        }
                    }
                }
            } else {
                $Report[$Index][$Service]['Database/Config/'.$Service.'.ini'] = '<div class="badge badge-primary">Konfiguration fehlt</div>';
            }
        }

        $Report = new Status( $Report );
        $Report->setRouteUpdate( $this->useRoute( 'Update' ) );

        $View->setContent( $Report->getContent() );

        return $View;
    }

    protected function setupDataStructure()
    {
        // TODO: Implement setupDataStructure() method.
    }
}
