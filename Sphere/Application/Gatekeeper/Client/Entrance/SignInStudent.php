<?php
namespace KREDA\Sphere\Application\Gatekeeper\Client\Entrance;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Template;

class SignInStudent extends Shell implements IElementInterface
{
    /** @var IBridgeInterface $Template */
    private $Template = null;

    /**
     * @return string
     */
    public function getContent()
    {
        $this->Template = Template::getTemplate( __DIR__.'/SignInStudent.twig' );

        return $this->Template->getContent();
    }

}
