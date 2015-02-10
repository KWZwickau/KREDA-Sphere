<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\EntityAction;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Common\Frontend\Form\AbstractForm;

/**
 * Class Person
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Person extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @param TblConsumer $tblConsumer
     */
    function __construct( TblConsumer $tblConsumer = null )
    {

        $this->setDatabaseHandler( 'Management', 'Person', $this->getConsumerSuffix( $tblConsumer ) );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

        Gatekeeper::serviceAccount()->executeChangePerson(
            $this->actionCreatePerson( 'Herr', 'Bernd', 'DAS', 'Brot', 'Kastenförmig', 'NA' ),
            Gatekeeper::serviceAccount()->entityAccountByUsername( 'Bernd' )
        );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblPerson
     */
    public function entityPersonById( $Id )
    {

        return parent::entityPersonById( $Id );
    }

    /**
     * @return bool|TblPerson[]
     */
    public function entityPersonAll()
    {

        return parent::entityPersonAll();
    }

    /**
     * @param AbstractForm $View
     *
     * @param null|string  $Salutation
     * @param null|string  $FirstName
     * @param null|string  $MiddleName
     * @param null|string  $LastName
     * @param null|string  $Gender
     * @param null|string  $Birthday
     *
     * @return AbstractForm
     */
    public function executeCreatePerson(
        AbstractForm &$View = null,
        $Salutation,
        $FirstName,
        $MiddleName,
        $LastName,
        $Gender,
        $Birthday
    ) {

        $Error = false;

        if (null !== $Salutation && empty( $Salutation )) {
            $View->setError( 'Salutation', 'Bitte geben Sie eine gültige Route ein' );
        }
        if (null !== $FirstName && empty( $FirstName )) {
            $View->setError( 'FirstName', 'Bitte geben Sie eine gültige Route ein' );
        }
        if (null !== $MiddleName && empty( $MiddleName )) {
            $View->setError( 'MiddleName', 'Bitte geben Sie eine gültige Route ein' );
        }
        if (null !== $LastName && empty( $LastName )) {
            $View->setError( 'LastName', 'Bitte geben Sie eine gültige Route ein' );
        }
        if (null !== $Gender && empty( $Gender )) {
            $View->setError( 'Gender', 'Bitte geben Sie eine gültige Route ein' );
        }
        if (null !== $Birthday && empty( $Birthday )) {
            $View->setError( 'Birthday', 'Bitte geben Sie eine gültige Route ein' );
        }

        if (!$Error) {

            $View->setSuccess( 'Salutation', 'Route wurde angelegt' );
        }
        return $View;
    }
}
