<?php
namespace KREDA\Sphere\Client\Frontend\Form\Type;

use KREDA\Sphere\Client\Frontend\Button\AbstractType as AbstractButton;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Common\Signature\Type\GetSignature;

/**
 * Class Form
 *
 * @package KREDA\Sphere\Client\Frontend\Form\Type
 */
class Form extends AbstractType
{

    /**
     * @param FormGroup|FormGroup[]                $FormGroup
     * @param null|AbstractButton|AbstractButton[] $AbstractButton
     * @param string                               $FormAction
     * @param array                                $FormData
     */
    public function __construct( $FormGroup, $AbstractButton = null, $FormAction = '', $FormData = array() )
    {

        if (!is_array( $FormGroup )) {
            $FormGroup = array( $FormGroup );
        }
        $this->GridGroupList = $FormGroup;

        if (!is_array( $AbstractButton ) && null !== $AbstractButton) {
            $AbstractButton = array( $AbstractButton );
        } elseif (empty( $AbstractButton )) {
            $AbstractButton = array();
        }
        $this->GridButtonList = $AbstractButton;

        $this->Template = $this->extensionTemplate( __DIR__.'/Form.twig' );
//        $this->Template->setVariable( 'UrlBase', $this->extensionRequest()->getUrlBase() );
        if (!empty( $FormData )) {
            $this->Template->setVariable( 'FormAction', $this->extensionRequest()->getUrlBase().$FormAction );
            $this->Template->setVariable( 'FormData', '?'.http_build_query(
                    ( new GetSignature() )->createSignature(
                        $FormData, $FormAction
                    )
                ) );
        } else {
            if (empty( $FormAction )) {
                $this->Template->setVariable( 'FormAction', $FormAction );
            } else {
                $this->Template->setVariable( 'FormAction', $this->extensionRequest()->getUrlBase().$FormAction );
            }
        }
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'FormButtonList', $this->GridButtonList );
        $this->Template->setVariable( 'GridGroupList', $this->GridGroupList );
        $this->Template->setVariable( 'Hash', $this->getHash() );
        return $this->Template->getContent();
    }

}
