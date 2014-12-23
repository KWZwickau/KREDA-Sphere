<?php
namespace KREDA\Sphere\Application\Assistance\Frontend\Support\Youtrack;

use KREDA\Sphere\Common\AbstractFrontend;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class Ticket
 *
 * @package KREDA\Sphere\Application\Assistance\Frontend\Support\Youtrack
 */
class Ticket extends AbstractFrontend
{

    /**
     * @throws TemplateTypeException
     */
    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/Ticket.twig' );
    }

    public function setErrorEmptySubject()
    {

        $this->Template->setVariable( 'TicketSubjectGroup', 'has-error has-feedback' );
        $this->Template->setVariable( 'TicketSubjectFeedbackIcon',
            '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' );
        $this->Template->setVariable( 'TicketSubjectFeedbackMessage',
            '<span class="help-block text-left">Bitte geben Sie ein Thema ein</span>' );
    }

    public function setErrorEmptyMessage()
    {

        $this->Template->setVariable( 'TicketMessageGroup', 'has-error has-feedback' );
        $this->Template->setVariable( 'TicketMessageFeedbackIcon',
            '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' );
        $this->Template->setVariable( 'TicketMessageFeedbackMessage',
            '<span class="help-block text-left">Bitte geben Sie ein Mitteilung ein</span>' );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }

}
