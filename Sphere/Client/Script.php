<?php
namespace KREDA\Sphere\Client;

use KREDA\Sphere\Common\AbstractExtension;

/**
 * Class Script
 *
 * @package KREDA\Sphere\Client
 */
class Script extends AbstractExtension
{

    /** @var array $SourceList */
    private static $SourceList = array();
    /** @var array $ModuleList */
    private static $ModuleList = array();

    /**
     * Default
     */
    private function __construct()
    {

        /**
         * Source (Library)
         */

        $this->Source(
            'jQuery', '/Library/jQuery/1.11.1/dist/jquery.min.js',
            "'undefined' !== typeof jQuery"
        );
        $this->Source(
            'Moment.js', '/Library/Moment.Js/2.8.4/min/moment-with-locales.min.js',
            "'undefined' !== typeof moment"
        );
        $this->Source(
            'Bootstrap', '/Library/Bootstrap/3.2.0/dist/js/bootstrap.min.js',
            "'function' === typeof jQuery().emulateTransitionEnd"
        );
        $this->Source(
            'jQuery.Selecter', '/Library/jQuery.Selecter/3.2.4/jquery.fs.selecter.min.js',
            "'undefined' !== typeof jQuery.fn.selecter"
        );
        $this->Source(
            'jQuery.Stepper', '/Library/jQuery.Stepper/3.0.8/jquery.fs.stepper.min.js',
            "'undefined' !== typeof jQuery.fn.stepper"
        );
        $this->Source(
            'jQuery.CheckBox', '/Library/jQuery.iCheck/1.0.2/icheck.min.js',
            "'undefined' !== typeof jQuery.fn.iCheck"
        );
        $this->Source(
            'jQuery.DataTable', '/Library/jQuery.DataTables/1.10.4/media/js/jquery.dataTables.min.js',
            "'undefined' !== typeof jQuery.fn.DataTable"
        );
        $this->Source(
            'jQuery.DataTable.Responsive',
            '/Library/jQuery.DataTables/1.10.4/extensions/Responsive/js/dataTables.responsive.min.js',
            "'undefined' !== typeof jQuery.fn.DataTable.Responsive"
        );
        $this->Source(
            'Bootstrap.DataTable',
            '/Library/jQuery.DataTables.Plugins/1.0.1/integration/bootstrap/3/dataTables.bootstrap.min.js',
            "'undefined' !== typeof jQuery.fn.DataTable.ext.renderer.pageButton.bootstrap"
        );
        $this->Source(
            'Bootstrap.DatetimePicker',
            '/Library/Bootstrap.DateTimePicker/3.1.3/build/js/bootstrap-datetimepicker.min.js',
            "'undefined' !== typeof jQuery.fn.datetimepicker"
        );
        $this->Source(
            'Bootstrap.FileInput', '/Library/Bootstrap.FileInput/4.1.6/js/fileinput.min.js',
            "'undefined' !== typeof jQuery.fn.fileinput"
        );
        $this->Source(
            'Twitter.Typeahead', '/Library/Twitter.Typeahead/0.10.5/dist/typeahead.bundle.min.js',
            "'undefined' !== typeof jQuery.fn.typeahead"
        );
        $this->Source(
            'MathJax', '/Library/MathJax/2.5.0/MathJax.js?config=TeX-MML-AM_HTMLorMML-full',
            "'undefined' !== typeof MathJax"
        );

        /**
         * Module (jQuery plugin)
         */

        $this->Module(
            'ModAlways', array( 'Bootstrap', 'jQuery' )
        );
        $this->Module(
            'ModTable',
            array( 'Bootstrap.DataTable', 'jQuery.DataTable.Responsive', 'jQuery.DataTable', 'jQuery' )
        );
        $this->Module(
            'ModPicker', array( 'Bootstrap.DatetimePicker', 'Moment.js', 'jQuery' )
        );
        $this->Module(
            'ModSelecter', array( 'jQuery.Selecter', 'jQuery' )
        );
        $this->Module(
            'ModCompleter', array( 'Twitter.Typeahead', 'Bootstrap', 'jQuery' )
        );
        $this->Module(
            'ModUpload', array( 'Bootstrap.FileInput', 'Bootstrap', 'jQuery' )
        );
        $this->Module(
            'ModCheckBox', array( 'jQuery.CheckBox', 'jQuery' )
        );
        $this->Module(
            'ModMathJax', array( 'MathJax', 'jQuery' )
        );
    }

    /**
     * @param string $Alias
     * @param string $Location
     * @param string $Test
     */
    public function Source( $Alias, $Location, $Test )
    {

        $PathBase = $this->extensionRequest()->getPathBase();
        if (!in_array( $Alias, self::$SourceList )) {
            self::$SourceList[$Alias] = "Client.Source('".$Alias."','".$PathBase.$Location."',function(){return ".$Test.";});";
        }
    }

    /**
     * @param string $Alias
     * @param array  $Dependencies
     */
    public function Module( $Alias, $Dependencies = array() )
    {

        if (!in_array( $Alias, self::$ModuleList )) {
            self::$ModuleList[$Alias] = "Client.Module('".$Alias."',".json_encode( $Dependencies ).");";
        }
    }

    /**
     * @return Script
     */
    public static function getManager()
    {

        return new Script();
    }

    /**
     * @return string
     */
    function __toString()
    {

        return '<script type="text/javascript">'.implode( "\n", self::$SourceList )."\n".implode( "\n",
            self::$ModuleList ).'</script>';
    }

}
