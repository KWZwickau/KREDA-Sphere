<?php
namespace KREDA\Sphere\Client;

use KREDA\Sphere\Common\AbstractExtension;

/**
 * Class Style
 *
 * @package KREDA\Sphere\Client
 */
class Style extends AbstractExtension
{

    /** @var array $SourceList */
    private static $SourceList = array();

    /**
     * Default
     */
    private function __construct()
    {

        $this->Source( '/Library/Bootstrap/3.2.0/dist/css/bootstrap.min.css' );
        $this->Source( '/Library/Bootstrap.Glyphicons/1.9.0/glyphicons_halflings/web/html_css/css/glyphicons-halflings.css' );
        $this->Source( '/Library/Bootstrap.Glyphicons/1.9.0/glyphicons/web/html_css/css/glyphicons.css' );
        $this->Source( '/Library/Bootstrap.Glyphicons/1.9.0/glyphicons_filetypes/web/html_css/css/glyphicons-filetypes.css' );
        $this->Source( '/Library/Bootstrap.Glyphicons/1.9.0/glyphicons_social/web/html_css/css/glyphicons-social.css' );
        $this->Source( '/Library/Bootstrap.FileInput/4.1.6/css/fileinput.min.css' );
        $this->Source( '/Library/Bootflat/2.0.4/bootflat/css/bootflat.min.css' );
        $this->Source( '/Library/Twitter.Typeahead.Bootstrap/1.0.0/typeaheadjs.css' );
        $this->Source( '/Library/Bootstrap.DateTimePicker/3.1.3/build/css/bootstrap-datetimepicker.min.css' );
        $this->Source( '/Library/jQuery.DataTables.Plugins/1.0.1/integration/bootstrap/3/dataTables.bootstrap.css' );
        $this->Source( '/Library/jQuery.DataTables/1.10.4/extensions/Responsive/css/dataTables.responsive.css' );
        $this->Source( '/Sphere/Client/Style/Style.css' );
    }

    /**
     * @param string $Location
     */
    public function Source( $Location )
    {

        $PathBase = $this->extensionRequest()->getPathBase();
        if (!in_array( sha1( $Location ), self::$SourceList )) {
            self::$SourceList[sha1( $Location )] = $PathBase.$Location;
        }
    }

    /**
     * @return Style
     */
    public static function getManager()
    {

        return new Style();
    }

    /**
     * @return string
     */
    function __toString()
    {

        $StyleList = self::$SourceList;
        array_walk( $StyleList, function ( &$Location ) {

            $Location = '<link rel="stylesheet" href="'.$Location.'">';
        } );
        return implode( "\n", $StyleList );
    }
}
