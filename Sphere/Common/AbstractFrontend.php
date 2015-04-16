<?php
namespace KREDA\Sphere\Common;

use KREDA\Sphere\Client\Component\Element\Repository\AbstractContent;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Redirect;
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
     * @return \KREDA\Sphere\Client\Frontend\Redirect
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
                if (isset( self::extensionSuperGlobal()->POST[$Match[1]][$Match[2]] )) {
                    $Template->setVariable( $VariableName,
                        htmlentities( self::extensionSuperGlobal()->POST[$Match[1]][$Match[2]], ENT_QUOTES ) );
                } elseif (isset( self::extensionSuperGlobal()->GET[$Match[1]][$Match[2]] )) {
                    $Template->setVariable( $VariableName,
                        htmlentities( self::extensionSuperGlobal()->GET[$Match[1]][$Match[2]], ENT_QUOTES ) );
                }
            } else {
                /**
                 * Next dimension
                 */
            }
        } else {
            if (isset( self::extensionSuperGlobal()->POST[$RequestKey] )) {
                $Template->setVariable( $VariableName,
                    htmlentities( self::extensionSuperGlobal()->POST[$RequestKey], ENT_QUOTES ) );
            } elseif (isset( self::extensionSuperGlobal()->GET[$RequestKey] )) {
                $Template->setVariable( $VariableName,
                    htmlentities( self::extensionSuperGlobal()->GET[$RequestKey], ENT_QUOTES ) );
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

        $Error = new Danger( __METHOD__.' MUST NOT create content.' );
        return $Error->getContent();
    }

}
