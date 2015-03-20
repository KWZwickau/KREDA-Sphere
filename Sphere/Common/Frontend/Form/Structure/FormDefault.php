<?php
namespace KREDA\Sphere\Common\Frontend\Form\Structure;

use KREDA\Sphere\Common\Frontend\Button\AbstractElement;
use KREDA\Sphere\Common\Frontend\Form\AbstractForm;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class FormDefault
 *
 * @package KREDA\Sphere\Common\Frontend\Form
 */
class FormDefault extends AbstractForm
{

    /**
     * @param GridFormGroup|GridFormGroup[]          $GridGroupList
     * @param null|AbstractElement|AbstractElement[] $FormButtonList
     * @param string                                 $FormAction
     *
     * @throws TemplateTypeException
     */
    function __construct( $GridGroupList, $FormButtonList = null, $FormAction = '' )
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
        $this->Template->setVariable( 'UrlBase', $this->extensionRequest()->getUrlBase() );
        $this->Template->setVariable( 'FormAction', $FormAction );
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
