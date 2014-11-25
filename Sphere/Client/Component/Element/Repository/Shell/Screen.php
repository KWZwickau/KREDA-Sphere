<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Shell;

use KREDA\Sphere\Application\Debugger;
use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
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

    /**
     * @throws TemplateTypeException
     */
    function __construct()
    {

        Debugger::addConstructorCall( __METHOD__ );

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

        Debugger::addMethodCall( __METHOD__ );

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

        return $this->addMessageToContent( $E, 'Error' );

    }

    /**
     * @param \Exception $E
     * @param string     $Name
     *
     * @return $this
     */
    private function addMessageToContent( \Exception $E, $Name = 'Error' )
    {

        Debugger::addMethodCall( __METHOD__ );

        $TraceList = '';
        foreach ((array)$E->getTrace() as $Index => $Trace) {
            $TraceList .= '<br/><samp class="text-info">'
                .( isset( $Trace['type'] ) && isset( $Trace['function'] ) ? '<br/>Method: '.$Trace['type'].$Trace['function'] : '<br/>Method: ' )
                .( isset( $Trace['class'] ) ? '<br/>Class: '.$Trace['class'] : '<br/>Class: ' )
                .( isset( $Trace['file'] ) ? '<br/>File: '.$Trace['file'] : '<br/>File: ' )
                .( isset( $Trace['line'] ) ? '<br/>Line: '.$Trace['line'] : '<br/>Line: ' )
                .'</samp>';
        }
        $Hit = '<samp class="text-danger"><p class="h6">'.$E->getMessage().'</p><br/>File: '.$E->getFile().'<br/>Line: '.$E->getLine().'</samp>'.$TraceList;
        $this->addToContent( new Container( new Error(
            $E->getCode() == 0 ? $Name : $E->getCode(), $Hit
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

        Debugger::addMethodCall( __METHOD__ );

        array_push( $this->PositionContent, $Container->getContent() );
        return $this;
    }

    /**
     * @param \Exception $E
     * @param string     $Identifier
     *
     * @return Screen
     */
    public function addException( \Exception $E, $Identifier = 'Unhandled Exception' )
    {

        return $this->addMessageToContent( $E, $Identifier );

    }

    /**
     * @return string
     */
    public function getContent()
    {

        Debugger::addMethodCall( __METHOD__ );

        $this->Template->setVariable( 'PositionNavigation', implode( '', $this->PositionNavigation ) );
        $this->Template->setVariable( 'PositionContent', implode( '', $this->PositionContent ) );
        $this->Template->setVariable( 'PositionDebugger', Debugger::getProtocol() );
        $this->Template->setVariable( 'PositionRuntime', Debugger::getRuntime() );

        return $this->Template->getContent();
    }

}
