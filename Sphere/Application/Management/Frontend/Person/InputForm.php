<?php
namespace KREDA\Sphere\Application\Management\Frontend\Person;

use KREDA\Sphere\Application\Management\Frontend\Person;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipList;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PencilIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class InputForm
 *
 * @package KREDA\Sphere\Application\Management\Frontend\Person
 */
class InputForm extends AbstractFrontend
{


    /**
     * @param TblPerson $tblPerson
     *
     * @return Form
     */
    public static function formRelationship( TblPerson $tblPerson )
    {

        $tblRelationshipList = Management::servicePerson()->entityPersonRelationshipAllByPerson( $tblPerson );

        if (!empty( $tblRelationshipList )) {
            /** @noinspection PhpUnusedParameterInspection */
            array_walk( $tblRelationshipList,
                function ( TblPersonRelationshipList &$tblPersonRelationshipList, $Index, TblPerson $tblPerson ) {

                    $Person = $tblPersonRelationshipList->getTblPersonA();
                    if ($Person->getId() == $tblPerson->getId()) {
                        $Person = $tblPersonRelationshipList->getTblPersonB();
                    }
                    $tblPersonRelationshipList->Person = $Person->getFullName().' ('.$Person->getTblPersonType()->getName().')';
                    $tblPersonRelationshipList->Relationship = $tblPersonRelationshipList->getTblPersonRelationshipType()->getName();
                    $tblPersonRelationshipList->Option = new Danger( 'Entfernen', '', new RemoveIcon() );
                }, $tblPerson );
        }
        return new Layout(
            new LayoutGroup( array(
                new LayoutRow( array(
                    new LayoutColumn( array(
                            new TableData( $tblRelationshipList, null, array(
                                'Person'       => 'Person',
                                'Relationship' => 'Beziehung',
                                'Option'       => 'Option'
                            ) ),
                            new Primary( 'Bearbeiten', '/Sphere/Management/Person/Relationship', new PencilIcon(),
                                array( 'tblPerson' => $tblPerson->getId() )
                            ),
                        )
                    )
                ) ),
            ), new LayoutTitle( 'Beziehungen' ) )
        );
    }

}
