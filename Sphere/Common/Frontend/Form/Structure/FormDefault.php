<?php
namespace KREDA\Sphere\Common\Frontend\Form\Structure;

use KREDA\Sphere\Client\Frontend\Button\AbstractType;
use KREDA\Sphere\Common\Frontend\Form\AbstractForm;
use KREDA\Sphere\Common\Signature\Type\GetSignature;

/**
 * Class FormDefault
 *
 * @package KREDA\Sphere\Common\Frontend\Form
 */
class FormDefault extends AbstractForm
{

    /**
     * @param GridFormGroup|GridFormGroup[]          $GridGroupList
     * @param null|AbstractType|\KREDA\Sphere\Client\Frontend\Button\AbstractType[] $FormButtonList
     * @param string                                 $FormAction
     * @param array                                  $FormData
     */
    function __construct( $GridGroupList, $FormButtonList = null, $FormAction = '', $FormData = array() )
    {

        if (!is_array( $GridGroupList )) {
            $GridGroupList = array( $GridGroupList );
        }
        $this->GridGroupList = $GridGroupList;

        if (!is_array( $FormButtonList ) && null !== $FormButtonList) {
            $FormButtonList = array( $FormButtonList );
        } elseif (empty( $FormButtonList )) {
            $FormButtonList = array();
        }
        $this->GridButtonList = $FormButtonList;

        $this->Template = $this->extensionTemplate( __DIR__.'/FormDefault.twig' );
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
