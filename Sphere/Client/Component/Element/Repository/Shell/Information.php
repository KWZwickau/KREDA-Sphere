<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Shell;

use KREDA\Sphere\Client\Component\Element\Repository\AbstractShell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class Information
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Shell
 */
class Information extends AbstractShell implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    /**
     * @param string $Message
     * @param string $Description
     * @param string $Title
     *
     * @throws TemplateTypeException
     */
    function __construct( $Message, $Description = 'Hinweis', $Title = 'Information' )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/Information.twig' );
        $this->Template->setVariable( 'InformationMessage', $Message );
        $this->Template->setVariable( 'InformationDescription', $Description );
        $this->Template->setVariable( 'InformationTitle', $Title );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }

}
