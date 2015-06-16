<?php
namespace KREDA\Sphere\Application\Transfer\Module\Export;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Common\AbstractApplication;
use MOC\V\Component\Document\Component\Bridge\Repository\PhpExcel;
use KREDA\Sphere\Client\Configuration;
use MOC\V\Component\Document\Document;

/**
 * Class FuxMedia
 *
 * @package KREDA\Sphere\Application\Transfer\Module\Import
 */
class Sfirm extends AbstractApplication
{
    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::registerClientRoute( $Configuration, '/Sphere/Transfer/Export/Sfirm',
            __CLASS__.'::frontendExportSfirm' )
            ->setParameterDefault( 'File', null );
    }

    public static function frontendExportSfirm()
    {
        $View = new Stage();
        $View->setTitle('Export');

        /** @var PhpExcel $export */
        $export = Document::getDocument("Sfirm.xls");
        $export->setValue($export->getCell("0","0"), "Erste Fälligkeit");
        $export->setValue($export->getCell("1","0"), "IBAN");
        $export->setValue($export->getCell("2","0"), "BIC");
        $export->setValue($export->getCell("3","0"), "Signaturdat.");
        $export->setValue($export->getCell("4","0"), "eigene Kto-Nr.");
        $export->setValue($export->getCell("5","0"), "Schulgeld");
        $export->setValue($export->getCell("6","0"), "Mandatsref.");
        $export->setValue($export->getCell("7","0"), "Erst-/Folgelastschrift");
        $export->setValue($export->getCell("8","0"), "Bank");
        $export->setValue($export->getCell("9","0"), "Mandant");
        $export->setValue($export->getCell("10","0"), "Debitoren-Nr.");
        $export->setValue($export->getCell("11","0"), "Beleg-Nr.");
        $export->setValue($export->getCell("12","0"), "Buchungstext");
        $export->setValue($export->getCell("13","0"), "Kto-Inhaber");
        $export->setValue($export->getCell("14","0"), "Schulgeld");

        $invoiceAllByIsConfirmedState = Billing::serviceInvoice()->entityInvoiceAllByIsConfirmedState(true);
        $invoiceAllByIsVoidState = Billing::serviceInvoice()->entityInvoiceAllByIsVoidState(true);
        $invoiceAllByIsPaidState = Billing::serviceInvoice()->entityInvoiceAllByIsPaidState(true);
        $invoiceHasFullPaymentAll = Billing::serviceBalance()->entityInvoiceHasFullPaymentAll();
        $invoiceHasExportDateAll = Billing::serviceBalance()->entityInvoiceHasExportDateAll();

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
        if ($invoiceAllByIsConfirmedState && $invoiceHasExportDateAll)
        {
            $invoiceAllByIsConfirmedState = array_udiff($invoiceAllByIsConfirmedState, $invoiceHasExportDateAll,
                function(TblInvoice $invoiceA, TblInvoice $invoiceB){
                    return $invoiceA->getId() - $invoiceB->getId();
                });
        }

        $counting = count($invoiceAllByIsConfirmedState);


        $Billing[] = array();
        $DebtorNumber[] = array();
        $IBAN[] = array();
        $BIC[] = array();
//        $Date = new \DateTime( 'now');
//        $ExportDate = $Date->format( 'd.m.Y H:i:s' );
        $InvoiceDate[] = array();
        $PaymentDate[] = array();
        $InvoiceNumber[] = array();
        $Student[] = array();
        $DebtorName[] = array();
        $AccountNumber[] = array();
        $DebtorFirst[] = array();
        $DebtorFollow[] = array();
        $DebtorBankName[] = array();
        $Course[] = array();
        $Commodity[] = array();

        if (!empty($invoiceAllByIsConfirmedState))
        {
            $j = 0;
            foreach($invoiceAllByIsConfirmedState as $invoiceByIsConfirmedState)
            {
                $PaymentDate[$j] = $invoiceByIsConfirmedState->getPaymentDate();
                $Billing[$j] = Billing::serviceInvoice()->sumPriceItemAllStringByInvoice( $invoiceByIsConfirmedState );
                $DebtorNumber[$j] = $invoiceByIsConfirmedState->getDebtorNumber();
                $IBAN[$j] = Billing::serviceBanking()->entityDebtorByDebtorNumber( $DebtorNumber[$j] )->getIBAN();
                $AccountNumber[$j] = substr($IBAN[$j],12,10);
                $BIC[$j] = Billing::serviceBanking()->entityDebtorByDebtorNumber( $DebtorNumber[$j] )->getBIC();
                $InvoiceDate[$j] = $invoiceByIsConfirmedState->getInvoiceDate();
                $InvoiceNumber[$j] = $invoiceByIsConfirmedState->getNumber();
                $Student[$j] = Management::servicePerson()->entityPersonById( $invoiceByIsConfirmedState->getServiceManagementPerson() )->getFullName();
                $DebtorName[$j] = $invoiceByIsConfirmedState->getDebtorFullName();
                $DebtorFirst[$j] = Billing::serviceBanking()->entityDebtorByDebtorNumber( $invoiceByIsConfirmedState->getDebtorNumber() )->getLeadTimeFirst();
                $DebtorFollow[$j] = Billing::serviceBanking()->entityDebtorByDebtorNumber( $invoiceByIsConfirmedState->getDebtorNumber() )->getLeadTimeFollow();
                $DebtorBankName[$j] = Billing::serviceBanking()->entityDebtorByDebtorNumber( $invoiceByIsConfirmedState->getDebtorNumber() )->getBankName();

                $tblPerson = Management::servicePerson()->entityPersonById( $invoiceByIsConfirmedState->getServiceManagementPerson() );
                $tblStudent = Management::serviceStudent()->entityStudentByPerson( $tblPerson );
                $Course[$j] = $tblStudent->getServiceManagementCourse()->getName();

                $tblInvoiceItemAll = Billing::serviceInvoice()->entityInvoiceItemAllByInvoice( $invoiceByIsConfirmedState );
                $p = 0;
                $CommodityTemp[] = array();
                foreach( $tblInvoiceItemAll as $tblInvoiceItem )
                {
                    $CommodityTemp[$p] = $tblInvoiceItem->getCommodityName();
                    $p++;
                }
                $Commodity[$j] = $CommodityTemp[0];

                $j++;
            }
        }

        for($i = 0;$i < $counting; $i++)    // ToDo Export
        {
            $Row = $i+1;
            $export->setValue($export->getCell("0",$Row), $PaymentDate[$i]);        //Erste Fälligkeit (Zahlungsdatum)
            $export->setValue($export->getCell("1",$Row), $IBAN[$i]);               //IBAN
            $export->setValue($export->getCell("2",$Row), $BIC[$i]);                //BIC
            $export->setValue($export->getCell("3",$Row), "");                      //Signaturdat. ?
            $export->setValue($export->getCell("4",$Row), $AccountNumber[$i]);      //KontoNummer
            $export->setValue($export->getCell("5",$Row), $Billing[$i]);            //Summe Geldbetrag (Schulgeld)
            $export->setValue($export->getCell("6",$Row), "");                      //Mandatsref. ?
            $export->setValue($export->getCell("7",$Row), $DebtorFirst[$i]." / ".$DebtorFollow[$i]); // ?
            $export->setValue($export->getCell("8",$Row), $DebtorBankName[$i]);     //BankName
            $export->setValue($export->getCell("9",$Row), $Course[$i]);             //Mandant
            $export->setValue($export->getCell("10",$Row), $DebtorNumber[$i]);      //Debitorennummer
            $export->setValue($export->getCell("11",$Row), $InvoiceNumber[$i]);     //Beleg-Nr.
            $export->setValue($export->getCell("12",$Row), $Commodity[$i]);         //Buchungstext
            $export->setValue($export->getCell("13",$Row), $DebtorName[$i]);        //Debitor Name
            $export->setValue($export->getCell("14",$Row), $Billing[$i]);           //ToDo Schulgeld doppelt?
        }

        $export->saveFile();

        $View->setContent(
            new Primary('Zurück zur Übersicht','/Sphere/Transfer')
        );

        return $View;
    }
}