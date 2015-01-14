<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Button\Structure;

use KREDA\Sphere\Common\AbstractFrontend\Button\AbstractGroup;
use KREDA\Sphere\Common\AbstractFrontend\Button\Element\ButtonDangerLink;
use KREDA\Sphere\Common\AbstractFrontend\Button\Element\ButtonPrimaryLink;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class GroupDefault
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Button\Structure
 */
class GroupDefault extends AbstractGroup
{

    /**
     * @param ButtonPrimaryLink[]|ButtonDangerLink[] $ButtonList
     *
     * @throws TemplateTypeException
     */
    function __construct( $ButtonList )
    {

        if (!is_array( $ButtonList )) {
            $ButtonList = array( $ButtonList );
        }
        $this->ButtonList = $ButtonList;

        $this->Template = Template::getTemplate( __DIR__.'/GroupDefault.twig' );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'ButtonList', $this->ButtonList );
        return $this->Template->getContent();
    }
}
