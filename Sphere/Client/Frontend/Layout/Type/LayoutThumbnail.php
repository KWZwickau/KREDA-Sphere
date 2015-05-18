<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Type;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;
use MOC\V\Core\FileSystem\Component\IBridgeInterface;

/**
 * Class LayoutThumbnail
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Type
 */
class LayoutThumbnail extends AbstractType
{

    const THUMBNAIL_TYPE_DEFAULT = '';
    const THUMBNAIL_TYPE_CIRCLE = 'img-circle';
    /**
     * @var
     */
    private $File;

    /**
     * @param IBridgeInterface $File
     * @param string           $Title
     * @param string           $Description
     * @param array            $ButtonList
     * @param string           $Type THUMBNAIL_TYPE_DEFAULT
     */
    public function __construct(
        IBridgeInterface $File,
        $Title,
        $Description = '',
        $ButtonList = array(),
        $Type = self::THUMBNAIL_TYPE_DEFAULT
    ) {

        if (!is_array( $ButtonList )) {
            $ButtonList = array( $ButtonList );
        }

        $this->Template = $this->extensionTemplate( __DIR__.'/LayoutThumbnail.twig' );
        $this->Template->setVariable( 'File', $File->getLocation() );

        $Size = getimagesize( $File->getRealPath() );

        $this->Template->setVariable( 'Height', $Size[1] );

        $this->Template->setVariable( 'Type', $Type );
        $this->Template->setVariable( 'Title', $Title );
        $this->Template->setVariable( 'Description', $Description );
        $this->Template->setVariable( 'ButtonList', $ButtonList );
        $this->File = $File;
    }
}
