<?php

/**
 * Aquatuning Software Development - Articles Accessory Direct Buy - Subscriber
 *
 * @category  Aquatuning
 * @package   Shopware\Plugins\AtsdArticleAccessoryDirectBuy
 * @copyright Copyright (c) 2013, Aquatuning GmbH
 */

namespace Shopware\AtsdArticleAccessoryDirectBuy\Subscriber\Controllers\Frontend;



/**
 * Aquatuning Software Development - Articles Accessory Direct Buy - Subscriber
 */

class Checkout implements \Enlight\Event\SubscriberInterface
{

	/**
	 * Main bootstrap object.
	 *
	 * @var \Shopware_Components_Plugin_Bootstrap
	 */

	protected $bootstrap;



	/**
	 * Shopware DI container.
	 *
	 * @var \Shopware\Components\DependencyInjection\Container
	 */

	protected $container;





	/**
	 * ...
	 *
	 * @param \Shopware_Components_Plugin_Bootstrap                $bootstrap
	 * @param \Shopware\Components\DependencyInjection\Container   $container
	 *
	 * @return \Shopware\AtsdArticleAccessoryDirectBuy\Subscriber\Controllers\Frontend\Checkout
	 */

	public function __construct( \Shopware_Components_Plugin_Bootstrap $bootstrap, \Shopware\Components\DependencyInjection\Container $container )
	{
		// set params
		$this->bootstrap = $bootstrap;
		$this->container = $container;
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
			'Shopware_Controllers_Frontend_Checkout::addAccessoriesAction::before'     => "beforeAddAccessories",
			'Shopware_Controllers_Frontend_Checkout::ajaxAddArticleCartAction::before' => "beforeAddAccessories"
		);
	}








	/**
	 * ...
	 *
	 * @param \Enlight_Hook_HookArgs   $arguments
	 *
	 * @return void
	 */

	public function beforeAddAccessories( \Enlight_Hook_HookArgs $arguments )
	{
		// get the controller
		/* @var $controller \Shopware_Controllers_Frontend_Checkout */
		$controller = $arguments->getSubject();

		// get parameters
		$request    = $controller->Request();

		// get the quantity
		$accessoriesQuantity = $request->getParam( "sAddAccessoriesQuantity" );

		// multiple quantities?
		if ( ( !is_array( $accessoriesQuantity ) ) and ( substr_count( $accessoriesQuantity, ";" ) > 0 ) )
		{
			// explode them
			$arr = explode( ";", $accessoriesQuantity );

			// and set as array
			$request->setParam( "sAddAccessoriesQuantity", $arr );
		}
	}





}