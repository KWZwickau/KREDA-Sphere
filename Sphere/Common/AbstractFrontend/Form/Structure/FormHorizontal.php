<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Form\Structure;

use KREDA\Sphere\Common\AbstractFrontend\Form\AbstractForm;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class FormHorizontal
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Form
 */
class FormHorizontal extends AbstractForm
{

    /**
     * @param GridGroup|GridGroup[] $GridGroupList
     * @param string                $FormSubmit
     * @param string                $FormAction
     *
     * @throws TemplateTypeException
     */
    function __construct( $GridGroupList, $FormSubmit = 'Absenden', $FormAction = '' )
    {

        if (!is_array( $GridGroupList )) {
            $GridGroupList = array( $GridGroupList );
        }
        $this->GridGroupList = $GridGroupList;

        $this->Template = Template::getTemplate( __DIR__.'/FormHorizontal.twig' );
        $this->Template->setVariable( 'UrlBase', HttpKernel::getRequest()->getUrlBase() );
        $this->Template->setVariable( 'FormAction', $FormAction );
        $this->Template->setVariable( 'FormSubmit', $FormSubmit );
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
