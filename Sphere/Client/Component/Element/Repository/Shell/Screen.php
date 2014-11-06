<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Shell;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class Screen
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Shell
 */
class Screen extends Shell implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;
    /** @var Container[] $PositionNavigation */
    private $PositionNavigation = array();
    /** @var Container[] $PositionContent */
    private $PositionContent = array();

    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/Screen.twig' );
        $this->Template->setVariable( 'PathBase', HttpKernel::getRequest()->getPathBase() );
    }

    /**
     * @param Container $Container
     *
     * @return Screen
     */
    public function addToNavigation( Container $Container )
    {

        array_push( $this->PositionNavigation, $Container->getContent() );
        return $this;
    }

    /**
     * @param \Exception $E
     *
     * @return Screen
     */
    public function addError( \Exception $E )
    {

        $TraceList = '';
        foreach ((array)$E->getTrace() as $Index => $Trace) {
            $TraceList .= '<br/><samp class="text-info">'
                .( isset( $Trace['type'] ) && isset( $Trace['function'] ) ? '<br/>Method: '.$Trace['type'].$Trace['function'] : '<br/>Method: ' )
                .( isset( $Trace['class'] ) ? '<br/>Class: '.$Trace['class'] : '<br/>Class: ' )
                .( isset( $Trace['file'] ) ? '<br/>File: '.$Trace['file'] : '<br/>File: ' )
                .( isset( $Trace['line'] ) ? '<br/>Line: '.$Trace['line'] : '<br/>Line: ' )
                .'</samp>';
        }
        $Hit = '<samp class="text-danger"><p class="h6">'.$E->getMessage().'</p>File: '.$E->getFile().'<br/>Line: '.$E->getLine().'</samp>'.$TraceList;
        $this->addToContent( new Container( new Error(
            $E->getCode() == 0 ? 'Error' : $E->getCode(), $Hit
        ) ) );
        return $this;
    }

    /**
     * @param Container $Container
     *
     * @return Screen
     */
    public function addToContent( Container $Container )
    {

        array_push( $this->PositionContent, $Container->getContent() );
        return $this;
    }

    /**
     * @param \Exception $E
     *
     * @return Screen
     */
    public function addException( \Exception $E )
    {

        $TraceList = '';
        foreach ((array)$E->getTrace() as $Index => $Trace) {
            $TraceList .= '<br/><samp class="text-info">'
                .( isset( $Trace['type'] ) && isset( $Trace['function'] ) ? '<br/>Method: '.$Trace['type'].$Trace['function'] : '<br/>Method: ' )
                .( isset( $Trace['class'] ) ? '<br/>Class: '.$Trace['class'] : '<br/>Class: ' )
                .( isset( $Trace['file'] ) ? '<br/>File: '.$Trace['file'] : '<br/>File: ' )
                .( isset( $Trace['line'] ) ? '<br/>Line: '.$Trace['line'] : '<br/>Line: ' )
                .'</samp>';
        }
        $Hit = '<samp class="text-danger"><p class="h6">'.$E->getMessage().'</p>File: '.$E->getFile().'<br/>Line: '.$E->getLine().'</samp>'.$TraceList;
        $this->addToContent( new Container( new Error(
            $E->getCode() == 0 ? 'Exception' : $E->getCode(), $Hit
        ) ) );
        return $this;

    }

    /**
     * @return string
     */
    public function getContent()
    {

        ob_start();
        print_r( $_REQUEST );
        $Parameter = ob_get_clean();

        $Request =
            '<div class="navbar-fixed-bottom container-fluid">'
            .'<div class="alert alert-info">'
            .$Parameter
            .'</div>'
            .'<div class="alert alert-info">'
            .'<strong>UrlPort</strong> '.HttpKernel::getRequest()->getPort().' '
            .'<strong>PathBase</strong> '.HttpKernel::getRequest()->getPathBase().' '
            .'<strong>UrlBase</strong> '.HttpKernel::getRequest()->getUrlBase().' '
            .'<strong>PathInfo</strong> '.HttpKernel::getRequest()->getPathInfo().' '
            .'</div>'
            .'</div>';

        $this->Template->setVariable( 'PositionNavigation', implode( '', $this->PositionNavigation ) );
        $this->Template->setVariable( 'PositionContent', implode( '', $this->PositionContent )
            .$Request
        );
        return $this->Template->getContent();
    }

}
