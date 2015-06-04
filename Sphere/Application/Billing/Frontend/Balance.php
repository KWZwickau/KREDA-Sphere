<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Balance\Entity\TblBalance;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Balance
 *
 * @package KREDA\Sphere\Application\Billing\Frontend
 */
class Balance extends AbstractFrontend
{

    public static function frontendBalance()
    {
        $View = new Stage();
        $View->setTitle('Posten');
        $View->setDescription('Offen');

        $invoiceAllByIsConfirmedState = Billing::serviceInvoice()->entityInvoiceAllByIsConfirmedState(true);
        $invoiceAllByIsVoidState = Billing::serviceInvoice()->entityInvoiceAllByIsVoidState(true);
        $invoiceAllByIsPaidState = Billing::serviceInvoice()->entityInvoiceAllByIsPaidState(true);


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

        foreach($invoiceAllByIsConfirmedState as $invoiceByIsConfirmedState)
        {

            $tblBalance = Billing::serviceBalance()->entityBalanceByInvoice( $invoiceByIsConfirmedState );
            $tblPaymentList = Billing::serviceBalance()->entityPaymentByBalance( $tblBalance );
            $AdditionInvoice = Billing::serviceInvoice()->sumPriceItemAllByInvoice( $invoiceByIsConfirmedState );

            if (!empty($tblPaymentList))
            {
                foreach($tblPaymentList as $Payment)
                {   $SumPayment[] = $Payment->getValue();  }
                $AdditionPayment = array_sum($SumPayment);
                unset ($SumPayment);
            }
            if (empty($AdditionPayment))
            { $AdditionPayment = 0; }


            $invoiceByIsConfirmedState->FullName = $invoiceByIsConfirmedState->getDebtorFullName();
            $invoiceByIsConfirmedState->PaidPayment = $AdditionPayment;
            $invoiceByIsConfirmedState->PaidInvoice = $AdditionInvoice;

        }

        $View->setContent(
        new TableData( $invoiceAllByIsConfirmedState, null,
            array(
                'Number'  => 'Nummer',
                'InvoiceDate' => 'Erstellungsdatum',
                'PaymentDate' => 'Bezahlungsdatum',
                'FullName' => 'Debitor',
                'DebtorNumber' => 'Debitorennummer',
                'PaidPayment' => 'Bezahlt',
                'PaidInvoice' => 'Gesamt'
                    )
                )
            );

        return $View;
    }
}