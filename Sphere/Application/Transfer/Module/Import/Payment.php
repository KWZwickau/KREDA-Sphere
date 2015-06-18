<?php
namespace KREDA\Sphere\Application\Transfer\Module\Import;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\FileUpload;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Common\AbstractApplication;
use MOC\V\Component\Document\Component\Bridge\Repository\PhpExcel;
use MOC\V\Component\Document\Document;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class Payment
 *
 * @package KREDA\Sphere\Application\Transfer\Module\Import
 */
class Payment extends AbstractApplication
{
    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {
        self::registerClientRoute( $Configuration, '/Sphere/Transfer/Import/Payment',
            __CLASS__.'::frontendImport' )
            ->setParameterDefault( 'File', null );
    }

    /**
     * @param $File
     *
     * @return Stage
     * @throws \MOC\V\Component\Document\Exception\DocumentTypeException
     */
    public static function frontendImport( $File )
    {
        $View = new Stage();
        $View->setTitle( 'Import' );
        $View->setDescription( 'Bezahldaten' );
        $View->setContent(
            new Layout(
                new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn( array(
                            $Form = new Form(
                                new FormGroup(
                                    new FormRow(
                                        new FormColumn(
                                            new FileUpload( 'File', 'Datei auswählen', 'Datei auswählen', null,
                                                array( 'showPreview' => false ) )
                                        )
                                    )
                                )
                                , new SubmitPrimary( 'Hochladen' ) )
                        ,
                            new Warning( 'Erlaubte Dateitypen: Excel (XLS,XLSX)' )
                        ) )
                    )
                )
            )
        );

        /** @var UploadedFile $File */
        if (null !== $File) {
            if ($File->getError()) {
                $Form->setError( 'File', 'Fehler' );
            } else {

                /**
                 * Prepare
                 */
                $File = $File->move( $File->getPath(), $File->getFilename().'.'.$File->getClientOriginalExtension() );
                /**
                 * Read
                 */
                /** @var PhpExcel $Document */
                $Document = Document::getDocument( $File->getPathname() );

                $X = $Document->getSheetColumnCount();
                $Y = $Document->getSheetRowCount();

                /**
                 * Header -> Location
                 */
                $Location = array(
                    'Datum'          => null,
                    'Betrag'                   => null,
                    'Rechnungsnummer'                => null,
                );
                for ($RunX = 0; $RunX < $X; $RunX++) {
                    $Value = $Document->getValue( $Document->getCell( $RunX, 0 ) );
                    if (array_key_exists( $Value, $Location )) {
                        $Location[$Value] = $RunX;
                    }
                }

                print_r($Location);
                print_r('<br>');

                /**
                 * Import
                 */
                if (!in_array( null, $Location, true ))
                {
                    for ($RunY = 1; $RunY < $Y; $RunY++)
                    {
                        $invoiceNumber = trim( $Document->getValue(
                            $Document->getCell( $Location['Rechnungsnummer'], $RunY )
                        ) );
                        $tblInvoice = Billing::serviceInvoice()->entityInvoiceByNumber($invoiceNumber);

                        if ($tblInvoice)
                        {
                            $tblBalance = Billing::serviceBalance()->entityBalanceByInvoice( $tblInvoice );

                            $date = trim( $Document->getValue(
                                $Document->getCell( $Location['Datum'], $RunY )
                            ) );

                            $value = trim( $Document->getValue(
                                $Document->getCell( $Location['Betrag'], $RunY )
                            ) );

                            Billing::serviceBalance()->actionCreatePayment($tblBalance, $value, new \DateTime($date));
                        }
                    }
                }
                else
                {
                    //ToDo map columns
                }
            }
        }

        return $View;
    }
}
