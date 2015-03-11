<?php
namespace KREDA\Sphere\Application\System\Frontend;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\FlashIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Cache\Frontend\Status;
use KREDA\Sphere\Common\Cache\Type\ApcSma;
use KREDA\Sphere\Common\Cache\Type\Apcu;
use KREDA\Sphere\Common\Cache\Type\ApcUser;
use KREDA\Sphere\Common\Cache\Type\OpCache;
use KREDA\Sphere\Common\Cache\Type\TwigCache;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitDanger;
use KREDA\Sphere\Common\Frontend\Form\Element\InputHidden;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;
use KREDA\Sphere\Common\Frontend\Redirect;

/**
 * Class Cache
 *
 * @package KREDA\Sphere\Application\System\Frontend
 */
class Cache extends AbstractFrontend
{

    /**
     * @param bool $Clear
     *
     * @return Stage
     */
    public static function stageStatus( $Clear )
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Cache' );
        $View->setDescription( 'Status' );
        if ($Clear) {
            ApcSma::clearCache();
            ApcUser::clearCache();
            OpCache::clearCache();
            TwigCache::clearCache();
        }
        if ($Clear && 'Force' == $Clear) {
            $View->setContent( new Redirect( '/Sphere/System/Cache/Status', 0 ) );
            return $View;
        }

        $Clear = new InputHidden( 'Clear' );
        $Clear->setDefaultValue( 'Force' );

        $View->setContent(
            new FormDefault( array(
                new GridFormGroup( new GridFormRow(
                    new GridFormCol( new Status( new Apcu() ) )
                ), new GridFormTitle( 'APCu' ) ),
                new GridFormGroup( new GridFormRow(
                    new GridFormCol( new Status( new OpCache() ) )
                ), new GridFormTitle( 'Zend OpCache' ) ),
                new GridFormGroup( new GridFormRow(
                    new GridFormCol( new Status( new TwigCache() ) )
                ), new GridFormTitle( 'Twig' ) ),
                new GridFormGroup( new GridFormRow(
                    new GridFormCol( $Clear )
                ) )
            ), new ButtonSubmitDanger( 'Clear', new FlashIcon() ) )
        );
        return $View;
    }
}
