<?php
namespace KREDA\Sphere\Application\Management\Service;

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
     * @param  array $PersonName
     * @param  array $BirthDetail
     * @param  array $PersonInformation
     *
     * @return AbstractForm
     */
    public function executeCreatePerson(
        AbstractForm &$View = null,
        $PersonName,
        $BirthDetail,
        $PersonInformation
    ) {

        /**
         * Skip to Frontend
         */
        if (null === $PersonName
            && null === $BirthDetail
            && null === $PersonInformation
        ) {
            return $View;
        }

        $Error = false;

        if (isset( $PersonName['Salutation'] ) && empty( $PersonName['Salutation'] )) {
            $View->setError( 'PersonName[Salutation]', 'Bitte geben Sie eine Anrede an' );
            $Error = true;
        }
        if (isset( $PersonName['First'] ) && empty( $PersonName['First'] )) {
            $View->setError( 'PersonName[First]', 'Bitte geben Sie einen Vornamen an' );
            $Error = true;
        }
        if (isset( $PersonName['Last'] ) && empty( $PersonName['Last'] )) {
            $View->setError( 'PersonName[Last]', 'Bitte geben Sie einen Nachnamen an' );
            $Error = true;
        }

        if (isset( $BirthDetail['Date'] ) && empty( $BirthDetail['Date'] )) {
            $View->setError( 'BirthDetail[Date]', 'Bitte geben Sie eine gültige Route ein' );
            $Error = true;
        }
        if (isset( $BirthDetail['City'] ) && empty( $BirthDetail['City'] )) {
            $View->setError( 'BirthDetail[City]', 'Bitte geben Sie eine gültige Route ein' );
            $Error = true;
        }

        if (isset( $PersonInformation['Nationality'] ) && empty( $PersonInformation['Nationality'] )) {
            $View->setError( 'PersonInformation[Nationality]', 'Bitte geben Sie eine gültige Route ein' );
            $Error = true;
        }
        if (isset( $PersonInformation['State'] ) && empty( $PersonInformation['State'] )) {
            $View->setError( 'PersonInformation[State]', 'Bitte geben Sie eine gültige Route ein' );
            $Error = true;
        }


        if (!$Error) {

            $View->setSuccess( 'Salutation', 'Route wurde angelegt' );
        } else {

            self::extensionDebugger()->screenDump( $PersonName, $BirthDetail, $PersonInformation );
        }
        return $View;
    }
}
