<?php

/**
 * Aquatuning Software Development - Articles Accessory Direct Buy - Subscriber
 *
 * @category  Aquatuning
 * @package   Shopware\Plugins\AtsdArticleAccessoryDirectBuy
 * @copyright Copyright (c) 2013, Aquatuning GmbH
 */

namespace Shopware\AtsdArticleAccessoryDirectBuy\Subscriber;

use Shopware_Components_Plugin_Bootstrap as Bootstrap;
use Enlight\Event\SubscriberInterface;


/**
 * Aquatuning Software Development - Articles Accessory Direct Buy - Subscriber
 */

class Controllers implements SubscriberInterface
{

	/**
	 * Main bootstrap object.
	 *
	 * @var Bootstrap
	 */

	protected $bootstrap;





	/**
	 * ...
	 *
	 * @param Bootstrap   $bootstrap
	 */

	public function __construct( Bootstrap $bootstrap )
	{
		// set params
		$this->bootstrap = $bootstrap;
	}






	/**
	 * Return the subscribed controller events.
	 *
	 * @return array
	 */

	public static function getSubscribedEvents()
	{
		// return the events
		return array(
			'Enlight_Controller_Dispatcher_ControllerPath_Backend_AtsdArticleAccessoryDirectBuy' => 'onGetBackendController'
		);
	}






    /**
     * ...
     *
     * @return string
     */

    public function onGetBackendController()
    {
        // load our template path
        Shopware()->Template()->addTemplateDir(
            $this->bootstrap->Path() . "Views/"
        );

        // return our controller
        return $this->bootstrap->Path(). "Controllers/Backend/AtsdArticleAccessoryDirectBuy.php";
    }








}