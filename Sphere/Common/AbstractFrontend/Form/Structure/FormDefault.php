<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Form\Structure;

use KREDA\Sphere\Common\AbstractFrontend\Button\AbstractElement;
use KREDA\Sphere\Common\AbstractFrontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\AbstractFrontend\Form\AbstractForm;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class FormDefault
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Form
 */
class FormDefault extends AbstractForm
{

    /**
     * @param GridGroup|GridGroup[]                  $GridGroupList
     * @param null|AbstractElement|AbstractElement[] $FormButtonList
     *
     * @throws TemplateTypeException
     */
    function __construct( $GridGroupList, $FormButtonList = null )
    {

        if (!is_array( $GridGroupList )) {
            $GridGroupList = array( $GridGroupList );
        }
        $this->GridGroupList = $GridGroupList;

        if (null == $FormButtonList) {
            $FormButtonList = array( new ButtonSubmitPrimary( 'Absenden' ) );
        } else {
            if (!is_array( $FormButtonList )) {
                $FormButtonList = array( $FormButtonList );
            }
        }

        $this->Template = Template::getTemplate( __DIR__.'/FormDefault.twig' );
        $this->Template->setVariable( 'UrlBase', HttpKernel::getRequest()->getUrlBase() );
        $this->Template->setVariable( 'FormButtonList', $FormButtonList );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'GridGroupList', $this->GridGroupList );
        return $this->Template->getContent();
    }

}
