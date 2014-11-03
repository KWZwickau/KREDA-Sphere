<?php
namespace KREDA\Sphere\Application\Gatekeeper\Client\Entrance;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Template;

class SignInTeacher extends Shell implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/SignInTeacher.twig' );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }

}
