<?php
namespace KREDA\Sphere\Application\Transfer;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Transfer\Module\Export;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DownloadIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TransferIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\UploadIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutThumbnail;
use MOC\V\Core\FileSystem\FileSystem;

/**
 * Class Transfer
 *
 * @package KREDA\Sphere\Application\Transfer
 */
class Transfer extends Export
{

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::setupApplicationAccess( 'Transfer' );

        if (Gatekeeper::serviceAccess()->checkIsValidAccess( 'Application:Transfer' )) {

            self::registerClientRoute( $Configuration, '/Sphere/Transfer', __CLASS__.'::frontendTransfer' );
            self::addClientNavigationMain( $Configuration, '/Sphere/Transfer', 'Datentransfer', new TransferIcon() );

            Module\Import::registerApplication( $Configuration );
            Module\Import\FuxMedia::registerApplication( $Configuration );
            Module\Export::registerApplication( $Configuration );
            Module\Export\Datev::registerApplication( $Configuration );
            Module\Export\Sfirm::registerApplication( $Configuration );
        }
    }

    /**
     * @return Stage
     */
    public static function frontendTransfer()
    {

        $View = new Stage();
        $View->setTitle( 'Datentransfer' );
        $View->setDescription( 'Import / Export' );

        $View->setContent(
            new Layout( array(
                    new LayoutGroup(
                        new LayoutRow( array(
                                new LayoutColumn( new LayoutThumbnail(
                                    FileSystem::getFileLoader( '/Sphere/Client/Style/Resource/fuxschool.gif' ),
                                    'FuxSchool', 'Schülerdaten',
                                    new Primary( 'Upload', '/Sphere/Transfer/Import/FuxMedia/FuxSchool/Student',
                                        new UploadIcon() )
                                ), 2 ),
                                new LayoutColumn( new LayoutThumbnail(
                                    FileSystem::getFileLoader( '/Sphere/Client/Style/Resource/fuxschool.gif' ),
                                    'FuxSchool', 'Klassendaten',
                                    new Primary( 'Upload', '/Sphere/Transfer/Import/FuxMedia/FuxSchool/Class',
                                        new UploadIcon() )
                                ), 2 ),
                            )
                        )
                        , new LayoutTitle( 'Import' ) ),
                    new LayoutGroup(
                        new LayoutRow( array(
                                new LayoutColumn( new LayoutThumbnail(
                                    FileSystem::getFileLoader( '/Sphere/Client/Style/Resource/datev_logo.png' ),
                                    'Datev', 'Rechnungen',
                                    new Primary( 'Download', '/Sphere/Transfer/Export/Datev', new DownloadIcon() )
                                ), 2 ),
                                new LayoutColumn( new LayoutThumbnail(
                                    FileSystem::getFileLoader( '/Sphere/Client/Style/Resource/datev_logo.png' ),
                                    'Sfirm', 'Rechnungen',
                                    new Primary( 'Download', '/Sphere/Transfer/Export/Sfirm', new DownloadIcon() )
                                ), 2 ),
                            )
                        )
                        , new LayoutTitle( 'Export' ) )
                )
            )
        );

        return $View;
    }
}
