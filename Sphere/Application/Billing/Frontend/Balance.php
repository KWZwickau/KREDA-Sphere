<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Balance\Entity\TblPayment;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Balance
 *
 * @package KREDA\Sphere\Application\Billing\Frontend
 */
class Balance extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function frontendBalance()
    {
        $View = new Stage();
        $View->setTitle('Posten');
        $View->setDescription('Offen');

        $invoiceAllByIsConfirmedState = Billing::serviceInvoice()->entityInvoiceAllByIsConfirmedState(true);
        $invoiceAllByIsVoidState = Billing::serviceInvoice()->entityInvoiceAllByIsVoidState(true);
        $invoiceAllByIsPaidState = Billing::serviceInvoice()->entityInvoiceAllByIsPaidState(true);
        $invoiceHasFullPaymentAll = Billing::serviceBalance()->entityInvoiceHasFullPaymentAll();

        if ($invoiceAllByIsConfirmedState && $invoiceAllByIsVoidState)
        {
            $invoiceAllByIsConfirmedState = array_udiff($invoiceAllByIsConfirmedState, $invoiceAllByIsVoidState,
                function(TblInvoice $invoiceA, TblInvoice $invoiceB){
                return $invoiceA->getId() - $invoiceB->getId();
            });
        }
        if ($invoiceAllByIsConfirmedState && $invoiceAllByIsPaidState)
        {
            $invoiceAllByIsConfirmedState = array_udiff($invoiceAllByIsConfirmedState, $invoiceAllByIsPaidState,
                function(TblInvoice $invoiceA, TblInvoice $invoiceB){
                return $invoiceA->getId() - $invoiceB->getId();
            });
        }
        if ($invoiceAllByIsConfirmedState && $invoiceHasFullPaymentAll)
        {
            $invoiceAllByIsConfirmedState = array_udiff($invoiceAllByIsConfirmedState, $invoiceHasFullPaymentAll,
                function(TblInvoice $invoiceA, TblInvoice $invoiceB){
                    return $invoiceA->getId() - $invoiceB->getId();
                });
        }
        if (!empty($invoiceAllByIsConfirmedState))
        {
            foreach($invoiceAllByIsConfirmedState as $invoiceByIsConfirmedState)
            {
                $tblBalance = Billing::serviceBalance()->entityBalanceByInvoice( $invoiceByIsConfirmedState );
                $AdditionInvoice = Billing::serviceInvoice()->sumPriceItemAllStringByInvoice( $invoiceByIsConfirmedState );
                $AdditionPayment = Billing::serviceBalance()->sumPriceItemStringByBalance( $tblBalance );

                $invoiceByIsConfirmedState->FullName = $invoiceByIsConfirmedState->getDebtorFullName();
                $invoiceByIsConfirmedState->PaidPayment = $AdditionPayment;
                $invoiceByIsConfirmedState->PaidInvoice = $AdditionInvoice;
                $invoiceByIsConfirmedState->Option = new Primary( 'Bezahlt', '/Sphere/Billing/Invoice/Pay',
                    new OkIcon(), array(
                        'Id' => $invoiceByIsConfirmedState->getId()
                    ) );
            }
        }

        $View->setContent(
            new TableData( $invoiceAllByIsConfirmedState, null,
                array(
                'Number'  => 'Nummer',
                'InvoiceDate' => 'Rechnungsdatum',
                'PaymentDate' => 'Zahlungsdatum',
                'FullName' => 'Debitor',
                'DebtorNumber' => 'Debitorennummer',
                'PaidPayment' => 'Bezahlt',
                'PaidInvoice' => 'Gesamt',
                'Option' => 'Option'
                )
            )
        );

        return $View;
    }

    /**
     * @return Stage
     */
    public static function frontendPayment()
    {
        $View = new Stage();
        $View->setTitle('Zahlungen');
        $View->setDescription('Importierte');

        $paymentList = Billing::serviceBalance()->entityPaymentAll();
        if ($paymentList)
        {
            array_walk($paymentList, function(TblPayment &$tblPayment)
            {
                $tblInvoice = $tblPayment->getTblBalance()->getServiceBillingInvoice();
                if ($tblInvoice)
                {
                    $tblPayment->InvoiceNumber = $tblInvoice->getNumber();
                    $tblPayment->InvoiceDate = $tblInvoice->getInvoiceDate();
                    $tblPayment->DebtorFullName = $tblInvoice->getDebtorFullName();
                    $tblPayment->DebtorNumber = $tblInvoice->getDebtorNumber();
                    $tblPayment->ValueString = $tblPayment->getValueString();
                }
            });
        }


        $View->setContent(
            new TableData( $paymentList, null,
                array(
                    'InvoiceNumber'  => 'Rechnungs-Nr.',
                    'InvoiceDate' => 'Rechnungsdatum',
                    'Date' => 'Zahlungseingangsdatum',
                    'DebtorFullName' => 'Debitor',
                    'DebtorNumber' => 'Debitorennummer',
                    'ValueString' => 'Betrag'
                )
            )
        );

        return $View;
    }
}