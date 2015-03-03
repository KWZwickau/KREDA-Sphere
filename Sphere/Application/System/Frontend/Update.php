<?php
namespace KREDA\Sphere\Application\System\Frontend;

use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageInfo;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;

/**
 * Class Update
 *
 * @package KREDA\Sphere\Application\System\Frontend
 */
class Update extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageStatus()
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Update' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );

        /** @var \Github\Api\Repo $Api */
        $Api = self::extensionGitHub()->api( 'repo' );
        $CommitMaster = current( $Api->commits()->all( 'KWZwickau', 'KREDA-Sphere', array( 'sha' => 'master' ) ) );
        $CommitDevelopment = current( $Api->commits()->all( 'KWZwickau', 'KREDA-Sphere',
            array( 'sha' => 'development' ) ) );

        $View->setContent(
            new MessageInfo(
                'Master: '.$CommitMaster['commit']['message']
                .'<br/>'.$CommitMaster['commit']['committer']['date']
            )
            .new MessageWarning(
                'Development: '.$CommitDevelopment['commit']['message']
                .'<br/>'.$CommitDevelopment['commit']['committer']['date']
            )
        );

        $View->addButton( 'Sphere/System/Update/Simulation', 'Simulation' );
        $View->addButton( 'Sphere/System/Update/Install', 'Installation' );

        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageSimulation()
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Update' );
        $View->setDescription( 'Simulation' );
        $View->setContent( System::serviceUpdate()->setupDatabaseSchema( true ) );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageInstall()
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Update' );
        $View->setDescription( 'Installation' );
        $View->setContent( System::serviceUpdate()->setupDatabaseSchema( false ) );
        return $View;
    }

}
