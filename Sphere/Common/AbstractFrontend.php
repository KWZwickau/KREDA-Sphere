<?php
namespace KREDA\Sphere\Common;

use KREDA\Sphere\Client\Component\Element\Repository\AbstractContent;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Frontend\Redirect;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractFrontend
 *
 *  - Methods MUST BE static
 *  - Methods MUST RETURN Stage
 *
 * @package KREDA\Sphere\Common
 */
abstract class AbstractFrontend extends AbstractContent
{

    /**
     * @param string $Route
     * @param int    $Timeout
     *
     * @return Redirect
     */
    final protected static function getRedirect( $Route, $Timeout = 15 )
    {

        return new Redirect( $Route, $Timeout );
    }

    /**
     * @param IBridgeInterface $Template
     * @param string           $RequestKey
     * @param string           $VariableName
     */
    final protected static function setPostValue( IBridgeInterface &$Template, $RequestKey, $VariableName )
    {

        if (preg_match( '!^(.*?)\[(.*?)\]$!is', $RequestKey, $Match )) {
            if (false === strpos( $Match[2], '[' )) {
                if (isset( $_POST[$Match[1]][$Match[2]] )) {
                    $Template->setVariable( $VariableName, $_POST[$Match[1]][$Match[2]] );
                } elseif (isset( $_GET[$Match[1]][$Match[2]] )) {
                    $Template->setVariable( $VariableName, $_GET[$Match[1]][$Match[2]] );
                }
            } else {
                /**
                 * Next dimension
                 */
            }
        } else {
            if (isset( $_POST[$RequestKey] )) {
                $Template->setVariable( $VariableName, $_POST[$RequestKey] );
            } elseif (isset( $_GET[$RequestKey] )) {
                $Template->setVariable( $VariableName, $_GET[$RequestKey] );
            }
        }
    }

    /**
     * @return string
     */
    final protected static function getUrlBase()
    {

        return self::extensionRequest()->getUrlBase();
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $Error = new MessageDanger( __METHOD__.' MUST NOT create content.' );
        return $Error->getContent();
    }

}
