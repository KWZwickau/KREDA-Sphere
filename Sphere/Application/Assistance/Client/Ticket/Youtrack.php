<?php
namespace KREDA\Sphere\Application\Assistance\Client\Ticket;

use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class Youtrack
 * @package KREDA\Sphere\Application\Assistance\Client\Ticket
 */
class Youtrack extends Element implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    /**
     * @param string $Subject
     * @param string $Message
     * @throws TemplateTypeException
     */
    function __construct($Subject, $Message)
    {

        $this->Template = Template::getTemplate(__DIR__ . '/Youtrack.twig');
        $this->Template->setVariable('TicketSubjectValue', $Subject);
        $this->Template->setVariable('TicketMessageValue', $Message);
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }

    public function setErrorEmptySubject()
    {

        $this->Template->setVariable('TicketSubjectGroup', 'has-error has-feedback');
        $this->Template->setVariable('TicketSubjectFeedbackIcon',
            '<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
        $this->Template->setVariable('TicketSubjectFeedbackMessage',
            '<span class="help-block text-left">Bitte geben Sie ein Thema ein</span>');
    }

    public function setErrorEmptyMessage()
    {

        $this->Template->setVariable('TicketMessageGroup', 'has-error has-feedback');
        $this->Template->setVariable('TicketMessageFeedbackIcon',
            '<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
        $this->Template->setVariable('TicketMessageFeedbackMessage',
            '<span class="help-block text-left">Bitte geben Sie ein Mitteilung ein</span>');
    }
}
