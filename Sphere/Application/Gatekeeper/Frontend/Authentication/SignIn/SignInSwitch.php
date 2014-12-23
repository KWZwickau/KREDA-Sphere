<?php
namespace KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication\SignIn;

use KREDA\Sphere\Application\Gatekeeper\Frontend\AbstractError;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class SignInSwitch
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication\SignIn
 */
class SignInSwitch extends AbstractError
{

    /**
     * @throws TemplateTypeException
     */
    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/SignInSwitch.twig' );
    }
}
