<?php
namespace KREDA\Sphere\Application\Demo\Service;

use KREDA\Sphere\Application\Demo\Service\DemoService\EntityAction;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class DemoService
 *
 * @package KREDA\Sphere\Application\Demo\Service
 */
class DemoService extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     *
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Demo', 'DemoService', $this->getConsumerSuffix() );
    }

    /**
     * @param AbstractType $abstractForm
     * @param string       $DemoCompleter
     * @param              $DemoTextArea
     *
     * @return AbstractType
     */
    public function executeCreateDemo( AbstractType $abstractForm, $DemoCompleter, $DemoTextArea )
    {

        if (null !== $DemoCompleter && empty( $DemoCompleter )) {
            $abstractForm->setError( 'DemoCompleter', 'Gib was ein' );
        }
        if (null !== $DemoTextArea && empty( $DemoTextArea )) {
            $abstractForm->setError( 'DemoTextArea', 'Gib was ein' );
        }
        if (!empty( $DemoCompleter )) {
            $this->actionCreateDemoCompleter( $DemoCompleter );
            $abstractForm->setSuccess( 'DemoCompleter', new Redirect( '/Sphere/Demo', 5 ) );
        }
        return $abstractForm;
    }

    /**
     * @return bool|DemoService\Entity\TblDemoCompleter[]
     */
    public function entityDemoCompleterAll()
    {

        return parent::entityDemoCompleterAll();
    }

}
