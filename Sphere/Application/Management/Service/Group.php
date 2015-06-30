<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Service\Group\Action;
use KREDA\Sphere\Application\Management\Service\Group\Entity\TblGroup;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Group
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Group extends Action
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     *
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Management', 'Group', $this->getConsumerSuffix() );
    }

    public function setupDatabaseContent()
    {

        $this->createGroup( 'Interessenten', '', false );
        $this->createGroup( 'Schüler', '', false );
        $this->createGroup( 'Sorgeberechtigte', '', false );
        $this->createGroup( 'Mitarbeiter', '', false );
        $this->createGroup( 'Sonstige', '', false );

        $this->createGroup( 'Pädagogisches Personal', '', true );
        $this->createGroup( 'Verwaltungspersonal', '', true );
        $this->createGroup( 'Geschäftspartner', '', true );
        $this->createGroup( 'Institutionen', '', true );
        $this->createGroup( 'Verbände', '', true );
        $this->createGroup( 'Partner', 'Patenschaften', true );
        $this->createGroup( 'Vereinsmitglieder', '', true );
        $this->createGroup( 'Schulpostempfänger', '', true );
        $this->createGroup( 'Spender', '', true );
        $this->createGroup( 'Lehrer', '', true );
        $this->createGroup( 'Erzieher', '', true );
    }

    /**
     * @param int $Id
     *
     * @return bool|TblGroup
     */
    public function fetchGroupById( $Id )
    {

        return parent::fetchGroupById( $Id );
    }

    /**
     * @return bool|TblGroup[]
     */
    public function fetchGroupAll()
    {

        return parent::fetchGroupAll();
    }

    /**
     * @param TblGroup $tblGroup
     *
     * @return int
     */
    public function countPersonAllByGroup( TblGroup $tblGroup )
    {

        return parent::countPersonAllByGroup( $tblGroup );
    }

    /**
     * @param TblGroup $tblGroup
     *
     * @return int
     */
    public function countCompanyAllByGroup( TblGroup $tblGroup )
    {

        return parent::countCompanyAllByGroup( $tblGroup );
    }

    /**
     * @param AbstractType $Form
     *
     * @param array        $Group
     *
     * @return AbstractType
     */
    public function executeCreateGroup( AbstractType &$Form, $Group )
    {

        /**
         * Skip to Frontend
         */
        if (null === $Group) {
            return $Form;
        }

        $Error = false;

        if (isset( $Group['Name'] ) && empty( $Group['Name'] )) {
            $Form->setError( 'Group[Name]', 'Bitte geben Sie einen eindeutigen Namen an' );
            $Error = true;
        } else {
            if ($this->fetchGroupByName( $Group['Name'] )) {
                $Form->setError( 'Group[Name]', 'Es existiert bereits eine Gruppe mit diesem Namen' );
                $Error = true;
            }
        }
        if (isset( $Group['Description'] ) && empty( $Group['Description'] )) {
            $Form->setError( 'Group[Description]', 'Bitte geben Sie eine Beschreibung an' );
            $Error = true;
        }

        if (!$Error) {
            $Entity = $this->createGroup( $Group['Name'], $Group['Description'], true );
            return new Success( 'Die Gruppe wurde erfolgreich angelegt' )
            .new Redirect( '/Sphere/Management/Group', 2, array( 'Id' => $Entity->getId() ) );
        }
        return $Form;
    }
}
