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

        if (null !== $FormButtonList) {
            if (!is_array( $FormButtonList )) {
                $FormButtonList = array( $FormButtonList );
            }
        }

        $this->Template = $this->extensionTemplate( __DIR__.'/FormDefault.twig' );
        $this->Template->setVariable( 'UrlBase', $this->extensionRequest()->getUrlBase() );
        $this->Template->setVariable( 'FormAction', $FormAction );
        $this->Template->setVariable( 'FormButtonList', $FormButtonList );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'GridGroupList', $this->GridGroupList );
        $this->Template->setVariable( 'Hash', $this->getHash() );
        return $this->Template->getContent();
    }

}
