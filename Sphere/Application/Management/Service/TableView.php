<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Service\TableView\Entity\TblView;
use KREDA\Sphere\Application\Management\Service\TableView\EntityAction;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Company
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class TableView  extends EntityAction
{
    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     *
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Management', 'TableView', $this->getConsumerSuffix() );
    }

    /**
     * @param $TypeName
     * @param $Name
     *
     * @return TableView\Entity\TblView
     */
    protected function actionCreateView( $TypeName, $Name)
    {
        return parent::actionCreateView( $TypeName, $Name );
    }

    /**
     * @param $Name
     * @param $DataType
     * @param TblView $tblView
     *
     * @return TableView\Entity\TblViewColumn|null|object
     */
    protected function actionCreateViewColumn(
        $Name,
        $DataType,
        TblView $tblView
    )
    {
        return parent::actionCreateViewColumn( $Name, $DataType, $tblView );
    }
}