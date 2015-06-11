<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Company\Entity\TblCompany;
use KREDA\Sphere\Application\Management\Service\Company\Entity\TblCompanyAddress;
use KREDA\Sphere\Application\Management\Service\Company\EntityAction;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Company
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Company  extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Management', 'Company', $this->getConsumerSuffix() );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

    }

    /**
     * @param $Id
     *
     * @return bool|TblCompany
     */
    public function entityCompanyById($Id)
    {
        return parent::entityCompanyById($Id);
    }

    /**
     * @return bool|TblCompany[]
     */
    public function entityCompanyAll()
    {
        return parent::entityCompanyAll();
    }

    /**
     * @param $Id
     *
     * @return bool|TblCompanyAddress
     */
    public function entityCompanyAddressById($Id)
    {
        return parent::entityCompanyAddressById($Id);
    }

    /**
     * @param TblCompany $tblCompany
     *
     * @return bool|TblAddress[]
     */
    public function entityAddressAllByCompany(TblCompany $tblCompany)
    {
        return parent::entityAddressAllByCompany($tblCompany);
    }

    /**
     * @param AbstractType $View
     * @param $Company
     *
     * @return AbstractType|string
     */
    public function executeCreateCompany(
        AbstractType &$View = null,
        $Company
    )
    {
        /**
         * Skip to Frontend
         */
        if (null === $Company
        ) {
            return $View;
        }

        $Error = false;

        if (isset( $Company['Name'] ) && empty(  $Company['Name'] )) {
            $View->setError( 'Company[Name]', 'Bitte geben Sie einen Namen an' );
            $Error = true;
        }

        if (!$Error)
        {
            if (($tblCompany = $this->actionCreateCompany($Company['Name'])))
            {
                $View .= new Success( 'Die Firma ' . $tblCompany->getName() . ' wurde erfolgreich angelegt' )
                    .new Redirect( '/Sphere/Management/Company/Edit', 1, array('Id' => $tblCompany->getId()) );
            }
            else
            {
                $View .= new Danger( 'Die Firma konnte nicht angelegt werden' )
                    .new Redirect( '/Sphere/Management/Company', 2 );
            };
        }

        return $View;
    }

    /**
     * @param TblCompany $tblCompany
     *
     * @return string
     */
    public function executeDestroyCompany(
        TblCompany $tblCompany
    )
    {
        if ($this->actionDestroyCompany( $tblCompany ))
        {
            return new Success( 'Die Firma wurde erfolgreich gelöscht' )
                .new Redirect( '/Sphere/Management/Company', 1 );
        }
        else
        {
            return new Warning( 'Die Firma konnte nicht gelöscht werden' )
                .new Redirect( '/Sphere/Management/Company', 2 );
        }
    }

    /**
     * @param AbstractType $View
     * @param TblCompany $tblCompany
     * @param $Company
     *
     * @return AbstractType|string
     */
    public function executeEditCompany(
        AbstractType &$View = null,
        TblCompany $tblCompany,
        $Company
    )
    {
        /**
         * Skip to Frontend
         */
        if (null === $Company)
        {
            return $View;
        }

        $Error = false;

        if (isset( $Company['Name'] ) && empty(  $Company['Name'] )) {
            $View->setError( 'Company[Name]', 'Bitte geben Sie einen Namen an' );
            $Error = true;
        }

        if (!$Error)
        {
            if ($this->actionEditCompany( $tblCompany, $Company['Name'] ))
            {
                $View .= new Success( 'Änderungen gespeichert, die Daten werden neu geladen...' )
                    .new Redirect( '/Sphere/Management/Company/Edit', 1 , array('Id'=>$tblCompany->getId()) );
            }
            else
            {
                $View .= new Danger( 'Änderungen konnten nicht gespeichert werden' )
                    .new Redirect( '/Sphere/Management/Company/Edit', 2 , array('Id'=>$tblCompany->getId()) );
            }
        }

        return $View;
    }

    /**
     * @param TblCompany $tblCompany
     * @param TblAddress $tblAddress
     *
     * @return string
     */
    public function executeAddCompanyAddress(
        TblCompany $tblCompany,
        TblAddress $tblAddress
    )
    {
        if ( $this->actionAddCompanyAddress($tblCompany, $tblAddress))
        {
            return new Success( 'Die Addresse wurde erfolgreich hinzugefügt' )
                .new Redirect( '/Sphere/Management/Company/Address/Edit', 0, array( 'Id' => $tblCompany->getId()) );
        }
        else
        {
            return new Warning( 'Die Addresse konnte nicht hinzugefügt werden' )
                .new Redirect( '/Sphere/Management/Company/Address/Edit', 2, array( 'Id' => $tblCompany->getId()) );
        }
    }

    /**
     * @param TblCompanyAddress $tblCompanyAddress
     *
     * @return string
     */
    public function executeRemoveCompanyAddress(
        TblCompanyAddress $tblCompanyAddress
    )
    {
        if ($this->actionRemoveCompanyAddress($tblCompanyAddress))
        {
            return new Success( 'Die Addresse wurde erfolgreich entfernt' )
                .new Redirect( '/Sphere/Management/Company/Edit', 0, array( 'Id' => $tblCompanyAddress->getTblCompany()->getId()) );
        }
        else
        {
            return new Warning( 'Die Addresse konnte nicht entfernt werden' )
            .new Redirect( '/Sphere/Management/Company/Edit', 2, array( 'Id' => $tblCompanyAddress->getTblCompany()->getId()) );
        }
    }
}
