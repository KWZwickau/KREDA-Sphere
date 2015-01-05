<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Common\AbstractService;

/**
 * Class Database
 *
 * @package KREDA\Sphere\Application\System\Service
 */
class Database extends AbstractService
{

    /** @var array $ServiceList */
    private $ServiceList = array(
        'System' => array(
            'Protocol' => array( '' )
        ),
        'Gatekeeper' => array(
            'Access'   => array( '' ),
            'Account'  => array( '' ),
            'Consumer' => array( '' ),
            'Token'    => array( '' ),
        ),
        'Management' => array(
            'Person'    => array(
                'EGE',
                'ESZC'
            ),
            'Address'   => array(
                'EGE',
                'ESZC'
            ),
            'Education' => array(
                'EGE',
                'ESZC'
            ),
        ),
        'Graduation' => array(
            'Score' => array(
                'EGE',
                'ESZC'
            ),
            'Grade' => array(
                'EGE',
                'ESZC'
            ),
            'Weight' => array(
                'EGE',
                'ESZC'
            )
        ),
        'Billing'    => array(
            '' => array(
                'EGE',
                'ESZC'
            )
        )
    );

    /**
     * @return array
     */
    public function executeDatabaseStatus()
    {

        $Report = array();
        ksort( $this->ServiceList );
        foreach ((array)$this->ServiceList as $Application => $ServiceList) {
            $Config = __DIR__.'/../../../Common/Database/Config/'.$Application.'.ini';
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
                                $Report[$Application][$Application]['<span class="text-muted">Service:</span> '.$Service.' <span class="text-muted">Consumer:</span> '.( $Consumer ? $Consumer : '---' )]['<span class="text-muted">Server:</span> '.$Group['Host'].' <span class="text-muted">Schema:</span> '.$Group['Database'].( empty( $Consumer ) ? '' : '_'.$Consumer )] = '<div class="badge badge-success">Verbunden</div>';
                            } catch( \Exception $E ) {
                                $Report[$Application][$Application]['<span class="text-muted">Service:</span> '.$Service.' <span class="text-muted">Consumer:</span> '.( $Consumer ? $Consumer : '---' )]['<span class="text-muted">Server:</span> '.$Group['Host'].' <span class="text-muted">Schema:</span> '.$Group['Database'].( empty( $Consumer ) ? '' : '_'.$Consumer )] = '<div class="badge badge-danger">Fehler</div>';
                            }
                        } else {
                            $Report[$Application][$Application]['<span class="text-muted">Service:</span> '.$Service.' <span class="text-muted">Consumer:</span> '.( $Consumer ? $Consumer : '---' )]['-NA-'] = '<div class="badge badge-danger">Konfiguration fehlerhaft</div>';
                        }
                    }
                }
            } else {
                $Report[$Application][$Application]['System/Database/Config/'.$Application.'.ini']['-NA-'] = '<div class="badge badge-primary">Konfiguration fehlt</div>';
            }
        }
        return $Report;
    }
}
