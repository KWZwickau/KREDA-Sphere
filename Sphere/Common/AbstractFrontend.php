<?php
namespace KREDA\Sphere\Common;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Common\AbstractFrontend\Redirect;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractFrontend
 *
 *  - Methods MUST BE static
 *  - Methods MUST RETURN Stage
 *
 * @package KREDA\Sphere\Common
 */
abstract class AbstractFrontend extends Shell
{

    /**
     * @param string $Route
     * @param int    $Timeout
     *
     * @return Redirect
     */
    final static protected function getRedirect( $Route, $Timeout = 15 )
    {

        return new Redirect( $Route, $Timeout );
    }

    /**
     * @param IBridgeInterface $Template
     * @param string           $ElementName
     * @param string           $ErrorMessage
     */
    final static protected function setFormError( IBridgeInterface &$Template, $ElementName, $ErrorMessage )
    {

        $Template->setVariable(
            $ElementName.'Group', 'has-error has-feedback'
        );
        $Template->setVariable(
            $ElementName.'FeedbackIcon', '<span class="glyphicon glyphicon-remove form-control-feedback"></span>'
        );
        $Template->setVariable(
            $ElementName.'FeedbackMessage', '<span class="help-block text-left">'.$ErrorMessage.'</span>'
        );
    }

    /**
     * @param IBridgeInterface $Template
     * @param string           $RequestKey
     * @param string           $VariableName
     */
    final static protected function setRequestValue( IBridgeInterface &$Template, $RequestKey, $VariableName )
    {

        if (isset( $_REQUEST[$RequestKey] )) {
            $Template->setVariable( $VariableName, $_REQUEST[$RequestKey] );
        }
    }

    /**
     * {{ ${RequestKey}Value }}
     *
     * @param IBridgeInterface $Template
     */
    final static protected function setRequestValues( IBridgeInterface &$Template )
    {

        foreach ((array)$_REQUEST as $Key => $Value) {
            if (is_string( $Value )) {
                $Template->setVariable( $Key.'Value', $Value );
            }
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getContent()
    {

        throw new \Exception( __CLASS__.' MUST NOT create content.' );
    }
}
