<?php
namespace KREDA\Sphere\Application\Graduation\Service;

use KREDA\Sphere\Application\Graduation\Service\Grade\Entity\TblGradeType;
use KREDA\Sphere\Application\Graduation\Service\Grade\EntityAction;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Grade
 *
 * @package KREDA\Sphere\Application\Graduation\Service
 */
class Grade extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     *
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Graduation', 'Grade', $this->getConsumerSuffix() );
    }

    public function setupDatabaseContent()
    {

        $this->actionCreateGradeType( 'LK', 'Leistungskontrolle' );
        $this->actionCreateGradeType( 'KA', 'Klassenarbeit' );
    }

    /**
     * @return bool|TblGradeType[]
     */
    public function entityGradeTypeAll()
    {

        return parent::entityGradeTypeAll();
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblGradeType
     */
    public function entityGradeTypeById( $Id )
    {

        return parent::entityGradeTypeById( $Id );
    }

    /**
     * @param string $Acronym
     *
     * @return bool|TblGradeType
     */
    public function entityGradeTypeByAcronym( $Acronym )
    {

        return parent::entityGradeTypeByAcronym( $Acronym );
    }

    public function executeChangeGradeTypeActiveState( $Id )
    {

        echo "Test".$Id;
        // $this->actionGradeTypeChangeState( $Id );
    }

    /**
     * @param \KREDA\Sphere\Client\Frontend\Form\AbstractType $View
     *
     * @param null|string  $Acronym
     * @param null|string  $Name
     *
     * @return \KREDA\Sphere\Client\Frontend\Form\AbstractType
     */
    public function executeCreateGradeType(

        AbstractType &$View = null,
        $Acronym,
        $Name
    ) {

        if (null !== $Acronym && empty( $Acronym )) {
            $View->setError( 'Acronym', 'Bitte geben Sie ein Kürzel ein' );
        }
        if (null !== $Name && empty( $Name )) {
            $View->setError( 'Name', 'Bitte geben Sie einen Namen ein' );
        }

        if (!empty( $Acronym ) && !empty( $Name )) {

            $Manager = $this->getEntityManager();
            $Entity = $Manager->getEntity( 'TblGradeType' )
                ->findOneBy( array( TblGradeType::ATTR_ACRONYM => $Acronym ) );
            if (null === $Entity) {
                $this->actionCreateGradeType( $Acronym, $Name );
                $View->setSuccess( 'Acronym', 'Zensurentyp wurde angelegt' );
                $View->setSuccess( 'Acronym', new Redirect( '/Sphere/Graduation/Grade/Type', 5 ) );
            } else {
                $View->setError( 'Acronym', 'Zensurentyp existiert bereits' );
            }

        }
        return $View;
    }
}
