<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Shell;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Template;

/**
 * Class Error
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Shell
 */
class Error extends Shell implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    function __construct( $Code )
    {

        $this->Template = Template::getTemplate( __DIR__.'/Error.twig' );
        $this->Template->setVariable( 'ErrorCode', $Code );
        switch ($Code) {
            case 404:
                $this->Template->setVariable( 'ErrorMessage',
                    'Die angeforderte Ressource konnte nicht gefunden werden' );
                break;
            default:
                $this->Template->setVariable( 'ErrorMessage', '' );
        }
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }

}
