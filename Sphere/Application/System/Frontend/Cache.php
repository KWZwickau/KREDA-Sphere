<?php
namespace KREDA\Sphere\Application\System\Frontend;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\FlashIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitDanger;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\HiddenField;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Cache\Frontend\Status;
use KREDA\Sphere\Common\Cache\Type\ApcSma;
use KREDA\Sphere\Common\Cache\Type\Apcu;
use KREDA\Sphere\Common\Cache\Type\ApcUser;
use KREDA\Sphere\Common\Cache\Type\Memcached;
use KREDA\Sphere\Common\Cache\Type\OpCache;
use KREDA\Sphere\Common\Cache\Type\TwigCache;

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
            Apcu::clearCache();
            Memcached::clearCache();
            OpCache::clearCache();
            TwigCache::clearCache();
        }
        if ($Clear && 'Force' == $Clear) {
            $View->setContent( new Redirect( '/Sphere/System/Cache/Status', 0 ) );
            return $View;
        }

        $Clear = new HiddenField( 'Clear' );
        $Clear->setDefaultValue( 'Force' );

        $View->setContent(
            new Form( array(
                new FormGroup( new FormRow(
                    new FormColumn( new Status( new Memcached() ) )
                ), new FormTitle( 'Memcached' ) ),
                new FormGroup( new FormRow(
                    new FormColumn( new Status( new Apcu() ) )
                ), new FormTitle( 'APCu' ) ),
                new FormGroup( new FormRow(
                    new FormColumn( new Status( new OpCache() ) )
                ), new FormTitle( 'Zend OpCache' ) ),
                new FormGroup( new FormRow(
                    new FormColumn( new Status( new TwigCache() ) )
                ), new FormTitle( 'Twig' ) ),
                new FormGroup( new FormRow(
                    new FormColumn( $Clear )
                ) )
            ), new SubmitDanger( 'Clear', new FlashIcon() ) )
        );
        return $View;
    }
}
