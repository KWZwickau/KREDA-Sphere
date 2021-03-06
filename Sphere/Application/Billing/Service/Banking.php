<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblReference;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtorCommodity;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblPaymentType;
use KREDA\Sphere\Application\Billing\Service\Banking\EntityAction;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Banking
 *
 * @package KREDA\Sphere\Application\Billing\Service
 */
class Banking extends EntityAction
{
    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Billing', 'Banking', $this->getConsumerSuffix() );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {
        /**
         * CommodityType
         */
        $this->actionCreatePaymentType('SEPA-Lastschrift');
        $this->actionCreatePaymentType('SEPA-Überweisung');
        $this->actionCreatePaymentType('Bar');
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return bool|Banking\Entity\TblDebtor[]
     */
    public  function entityDebtorAllByPerson(TblPerson $tblPerson)
    {
        return parent::entityDebtorAllByPerson($tblPerson);
    }

    /**
     * @param TblDebtor $tblDebtor
     *
     * @return bool|TblDebtorCommodity[]
     */
    public function entityCommodityDebtorAllByDebtor( TblDebtor $tblDebtor )
    {

        return parent::entityCommodityDebtorAllByDebtor( $tblDebtor );
    }

    /**
     * @param TblDebtor $tblDebtor
     *
     * @return array
     */
    public function entityCommodityAllByDebtor( TblDebtor $tblDebtor )
    {

        $tblDebtorCommodityList = $this->entityCommodityDebtorAllByDebtor($tblDebtor);
        $tblCommodity = array();
        foreach($tblDebtorCommodityList as $tblDebtorCommodity)
        {
            array_push($tblCommodity, $tblDebtorCommodity->getServiceBillingCommodity());
        }

        return $tblCommodity;
    }

    /**
     * @param $Id
     *
     * @return bool|TblDebtorCommodity
     */
    public function entityDebtorCommodityById( $Id )
    {
        return parent::entityDebtorCommodityById( $Id );
    }

    /**
     * @param TblDebtor $tblDebtor
     * @param TblCommodity $tblCommodity
     *
     * @return string
     */
    public function executeAddDebtorCommodity(
        TblDebtor $tblDebtor,
        TblCommodity $tblCommodity
    )
    {
        if ($this->actionAddDebtorCommodity($tblDebtor, $tblCommodity))
        {
            return new Success( 'Die Leistung ' . $tblCommodity->getName() . ' wurde erfolgreich hinzugefügt' )
            .new Redirect( '/Sphere/Billing/Banking/Commodity/Select', 0, array( 'Id' => $tblDebtor->getId()) );
        }
        else
        {
            return new Warning( 'Die Leistung ' . $tblCommodity->getName() . ' konnte nicht hinzugefügt werden' )
            .new Redirect( '/Sphere/Billing/Banking/Commodity/Select', 2, array( 'Id' => $tblDebtor->getId()) );
        }
    }

    /**
     * @param TblDebtorCommodity $tblDebtorCommodity
     *
     * @return string
     */
    public function executeRemoveDebtorCommodity(
        TblDebtorCommodity $tblDebtorCommodity
    )
    {
        if ($this->actionRemoveDebtorCommodity($tblDebtorCommodity))
        {
            return new Success( 'Die Leistung ' . $tblDebtorCommodity->getServiceBillingCommodity()->getName() . ' wurde erfolgreich entfernt' )
            .new Redirect( '/Sphere/Billing/Banking/Commodity/Select', 0, array( 'Id' => $tblDebtorCommodity->getTblDebtor()->getId()) );
        }
        else
        {
            return new Warning( 'Die Leistung ' .$tblDebtorCommodity->getServiceBillingCommodity()->getName() .  ' konnte nicht entfernt werden' )
            .new Redirect( '/Sphere/Billing/Banking/Commodity/Select', 2, array( 'Id' => $tblDebtorCommodity->getTblDebtor()->getId()) );
        }
    }

    /**
     * @param TblDebtor $tblDebtor
     * @param TblCommodity $tblCommodity
     *
     * @return bool|Banking\Entity\TblDebtorCommodity[]
     */
    public  function entityDebtorCommodityAllByDebtorAndCommodity(TblDebtor $tblDebtor, TblCommodity $tblCommodity)
    {
        return parent::entityDebtorCommodityAllByDebtorAndCommodity($tblDebtor, $tblCommodity);
    }

    /**
     * @param AbstractType $View
     * @param $Debtor
     * @param $Id
     *
     * @return AbstractType|string
     */
    public function executeAddDebtor(
        AbstractType &$View = null,
        $Debtor,
        $Id )
    {
        /**
         * Skip to Frontend
         */
        if ( null === $Debtor){
            return $View;
        }

        print_r($Debtor);

        $Error = false;
        if (isset($Debtor['DebtorNumber']) && empty( $Debtor['DebtorNumber'])) {
            $View->setError( 'Debtor[DebtorNumber]', 'Bitte geben sie die Debitorennummer an' );
            $Error = true;
        }
        if (isset($Debtor['DebtorNumber']) && Billing::serviceBanking()->entityDebtorByDebtorNumber( $Debtor['DebtorNumber'])) {
            $View->setError( 'Debtor[DebtorNumber]', 'Die Debitorennummer exisitiert bereits. Bitte geben Sie eine andere Debitorennummer an' );
            $Error = true;
        }
        if (isset($Debtor['LeadTimeFirst']) && empty( $Debtor['LeadTimeFirst'])) {
            $View->setError( 'Debtor[LeadTimeFirst]', 'Bitte geben sie den Ersteinzug an.' );
            $Error = true;
        }
        if (isset($Debtor['LeadTimeFirst']) &&  !is_numeric($Debtor['LeadTimeFirst'])) {
            $View->setError('Debtor[LeadTimeFirst]', 'Bitte geben sie eine Zahl an.');
            $Error = true;
        }
        if (isset($Debtor['LeadTimeFollow']) && empty( $Debtor['LeadTimeFollow'])) {
            $View->setError( 'Debtor[LeadTimeFollow]', 'Bitte geben sie den Folgeeinzug an.' );
            $Error = true;
        }
        if (isset($Debtor['LeadTimeFollow']) &&  !is_numeric($Debtor['LeadTimeFollow'])) {
            $View->setError('Debtor[LeadTimeFollow]', 'Bitte geben sie eine Zahl an.');
            $Error = true;
        }
        if (isset($Debtor['Reference']) && Billing::serviceBanking()->entityReferenceByReference( $Debtor['Reference'])) {
            $View->setError('Debtor[Reference]', 'Die Mandatsreferenz exisitiert bereits. Bitte geben Sie eine andere an');
            $Error = true;
        }

        if (!$Error) {

            $this->actionAddDebtor( $Debtor['DebtorNumber'],
                $Debtor['LeadTimeFirst'],
                $Debtor['LeadTimeFollow'],
                $Debtor['BankName'],
                $Debtor['Owner'],
                $Debtor['CashSign'],
                $Debtor['IBAN'],
                $Debtor['BIC'],
                $Debtor['Description'],
                $Debtor['PaymentType'],
                Management::servicePerson()->entityPersonById( $Id) );
            if(!empty($Debtor['Reference']))
            {
                $this->actionAddReference( $Debtor['Reference'],
                    $Debtor['DebtorNumber'],
                    $Debtor['ReferenceDate'],
                    Billing::serviceCommodity()->entityCommodityById($Debtor['Commodity']) );
            }
            return new Success( 'Der Debitor ist erfasst worden' )
            .new Redirect( '/Sphere/Billing/Banking', 2 );
        }
        return $View;
    }

    /**
     * @param AbstractType $View
     * @param TblDebtor $Debtor
     * @param $Reference
     * @return AbstractType|string
     */
    public function executeAddReference(
        AbstractType &$View = null,
        TblDebtor $Debtor,
        $Reference )
    {

        /**
         * Skip to Frontend
         */
        if ( null === $Reference){
            return $View;
        }

        $Error = false;
//        if( Billing::serviceBanking()->entityReferenceByDebtor( $Debtor ) ){
//            $View->setError( 'Reference[Reference]', 'Der Debitor besitzt eine gültige Referenz' );
//            $Error = true;
//        }
        if (isset($Reference['Reference']) && empty( $Reference['Reference'])) {
            $View->setError( 'Reference[Reference]', 'Bitte geben sie eine Mandatsreferenz an' );
            $Error = true;
        }
        if (isset($Reference['Reference']) && Billing::serviceBanking()->entityReferenceByReferenceActive( $Reference['Reference'])) {
            $View->setError('Reference[Reference]', 'Die Mandatsreferenz exisitiert bereits. Bitte geben Sie eine andere an');
            $Error = true;
        }
//        if (isset($Reference['ReferenceDate']) && empty( $Reference['ReferenceDate'])) {
//            $View->setError( 'Reference[ReferenceDate]', 'Bitte geben sie ein Referenzdatum an.' );
//            $Error = true;
//        }

        if (!$Error) {

            $this->actionAddReference( $Reference['Reference'],
                $Debtor->getDebtorNumber(),
                $Reference['ReferenceDate'],
                Billing::serviceCommodity()->entityCommodityById( $Reference['Commodity'] ));

            return new Success( 'Die Referenz ist erfasst worden' )
            .new Redirect( '/Sphere/Billing/Banking/Debtor/Reference', 0, array( 'Id' => $Debtor->getId() ) );
        }

        return $View;

    }

    /**
     * @param TblDebtor $tblDebtor
     *
     * @return string
     */
    public function executeDeleteReference( TblDebtor $tblDebtor )
    {
        if (null === $tblDebtor)
        {
            return '';
        }

        if ($this->actionRemoveReference( $tblDebtor ))
        {
            return new Success( 'Die Referenz wurde erfolgreich entfernt')
            .new Redirect( '/Sphere/Billing/Banking', 2 );
        }
        else
        {
            return new Danger( 'Die Referenz konnte nicht entfernt werden' )
            .new Redirect( '/Sphere/Billing/Banking', 2 );
        }

    }

    /**
     * @param TblReference $tblReference
     *
     * @return string
     */
    public function setBankingReferenceDeactivate( TblReference $tblReference )
    {

        if (null === $tblReference)
        {
            return '';
        }
        if ($this->actionDeactivateReference( $tblReference )) {
            return new Success('Die Deaktivierung ist erfasst worden')
            . new Redirect('/Sphere/Billing/Banking/Debtor/Reference', 2, array( 'Id' => $tblReference->getServiceBillingBanking()->getId() ) );
        }
        else
        {
            return new Danger( 'Die Referenz konnte nicht deaktiviert werden' )
            .new Redirect( '/Sphere/Billing/Banking/Debtor/Reference', 2, array( 'Id' => $tblReference->getServiceBillingBanking()->getId() ) );
        }
    }

    /**
     * @param TblDebtor $tblDebtor
     *
     * @return string
     */
    public function executeBankingDelete(
        TblDebtor $tblDebtor
    )
    {
        if (null === $tblDebtor)
        {
            return '';
        }

        if ($this->actionRemoveBanking($tblDebtor))
        {
            return new Success( 'Die Leistung wurde erfolgreich gelöscht')
            .new Redirect( '/Sphere/Billing/Banking', 2);
        }
        else
        {
            return new Danger( 'Die Leistung konnte nicht gelöscht werden' )
            .new Redirect( '/Sphere/Billing/Banking', 2);
        }
    }

    public function executeEditDebtor( AbstractType &$View = null,TblDebtor $tblDebtor, $Debtor )
    {
        /**
         * Skip to Frontend
         */
        if (null === $Debtor
        ) {
            return $View;
        }

        $Error = false;
        if (isset($Debtor['LeadTimeFirst']) && empty( $Debtor['LeadTimeFirst'])) {
            $View->setError( 'Debtor[LeadTimeFirst]', 'Bitte geben sie eine Vorlaufzeit ein' );
            $Error = true;
        }
        if (isset($Debtor['LeadTimeFollow']) && empty( $Debtor['LeadTimeFollow'])) {
            $View->setError( 'Debtor[LeadTimeFollow]', 'Bitte geben sie eine Vorlaufzeit ein' );
            $Error = true;
        }

        if(!$Error)
        {
            if ($this->actionEditDebtor(
                $tblDebtor,
                $Debtor['Description'],
                $Debtor['PaymentType'],
                $Debtor['Owner'],
                $Debtor['IBAN'],
                $Debtor['BIC'],
                $Debtor['CashSign'],
                $Debtor['BankName'],
                $Debtor['LeadTimeFirst'],
                $Debtor['LeadTimeFollow']
            )) {
                $View .= new Success( 'Änderungen sind erfasst' )
                    .new Redirect( '/Sphere/Billing/Banking/Debtor/View', 2, array( 'Id' => $tblDebtor->getId() ) );
            } else {
                $View .= new Danger( 'Änderungen konnten nicht gespeichert werden' )
                    .new Redirect( '/Sphere/Billing/Banking', 2);
            }
            return $View;
        }
        return $View;
    }

    /**
     * @return array|bool|TblDebtor[]
     */
    public function entityDebtorAll()
    {

        return parent::entityDebtorAll();
    }

    /**
     * @param TblDebtor $tblDebtor
     *
     * @return bool|TblReference[]
     */
    public function entityReferenceByDebtor ( TblDebtor $tblDebtor )
    {
        return parent::entityReferenceByDebtor( $tblDebtor );
    }

    /**
     * @param $tblReference
     *
     * @return bool|TblReference
     */
    public function entityReferenceById ( $tblReference )
    {
        return parent::entityReferenceById( $tblReference );
    }

    /**
     * @param TblDebtor $tblDebtor
     * @param TblCommodity $tblCommodity
     *
     * @return bool|TblReference
     */
    public function entityReferenceByDebtorAndCommodity(TblDebtor $tblDebtor, TblCommodity $tblCommodity)
    {
        return parent::entityReferenceByDebtorAndCommodity($tblDebtor, $tblCommodity);
    }

    /**
     * @param $Reference
     *
     * @return bool|TblReference
     */
    public function entityReferenceByReference ( $Reference )
    {
        return parent::entityReferenceByReference( $Reference );
    }

    /**
     * @param $Reference
     *
     * @return bool|TblReference
     */
    public function entityReferenceByReferenceActive ( $Reference )
    {
        return parent::entityReferenceByReferenceActive( $Reference );
    }

    /**
     * @param $Id
     *
     * @return bool|TblDebtor
     */
    public function entityDebtorById( $Id )
    {

        return parent::entityDebtorById( $Id );
    }

    /**
     * @param $DebtorNumber
     *
     * @return bool|TblDebtor
     */
    public function entityDebtorByDebtorNumber($DebtorNumber)
    {
        return parent::entityDebtorByDebtorNumber($DebtorNumber);
    }

    /**
     * @param $ServiceManagement_Person
     *
     * @return bool|Banking\Entity\TblDebtor[]
     */
    public function entityDebtorByServiceManagementPerson( $ServiceManagement_Person )
    {

        return parent::entityDebtorByServiceManagementPerson( $ServiceManagement_Person );
    }

    /**
     * @return array|bool|TblPaymentType[]
     */
    public function entityPaymentTypeAll()
    {
        return parent::entityPaymentTypeAll();
    }

    /**
     * @param $PaymentType
     *
     * @return bool|TblPaymentType
     */
    public function entityPaymentTypeByName($PaymentType)
    {
        return parent::entityPaymentTypeByName($PaymentType);
    }

    /**
     * @param $Id
     *
     * @return bool|TblPaymentType
     */
    public function entityPaymentTypeById( $Id )
    {
        return parent::entityPaymentTypeById( $Id );
    }

    /**
     * @param TblDebtor $tblDebtor
     *
     * @return int
     */
    public function entityLeadTimeByDebtor(TblDebtor $tblDebtor)
    {
        if (Billing::serviceInvoice()->checkInvoiceFromDebtorIsPaidByDebtor( $tblDebtor ) || Billing::serviceBalance()->checkPaymentFromDebtorExistsByDebtor( $tblDebtor ))
        {
            return $tblDebtor->getLeadTimeFollow();
        }
        else
        {
            return $tblDebtor->getLeadTimeFirst();
        }
    }

}