<?php

/**
 * Aquatuning Software Development - Articles Accessory Direct Buy - Subscriber
 *
 * @category  Aquatuning
 * @package   Shopware\Plugins\AtsdArticleAccessoryDirectBuy
 * @copyright Copyright (c) 2013, Aquatuning GmbH
 */

namespace Shopware\AtsdArticleAccessoryDirectBuy\Subscriber\Controllers\Backend;



/**
 * Aquatuning Software Development - Articles Accessory Direct Buy - Subscriber
 */

class Article implements \Enlight\Event\SubscriberInterface
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
	 * @return \Shopware\AtsdArticleAccessoryDirectBuy\Subscriber\Controllers\Backend\Article
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
			'Enlight_Controller_Action_PostDispatch_Backend_Article' => "loadArticleBackendModule",
		);
	}










	/**
	 * ...
	 *
	 * @param \Enlight_Event_EventArgs   $args
	 *
	 * @return void
	 */

	public function loadArticleBackendModule( \Enlight_Event_EventArgs $args )
	{
		// controller
		/* @var $controller \Shopware_Controllers_Backend_Article */
		$controller = $args->getSubject();

		// get the view
		$view = $controller->View();

		// add our template dir
		$view->addTemplateDir( $this->bootstrap->Path() . "Views/" );

		// if the controller action name equals "load" we have to load all application components.
		if ( $args->getRequest()->getActionName() === "load" )
		{
			// load extended templates
			$view->extendsTemplate( 'backend/atsd_article_accessory_direct_buy/article/view/detail/window.js' );
		}

		// if the controller action name equals "index" we have to extend the backend article application
		if ( $args->getRequest()->getActionName() === "index" )
			// load our app
			$view->extendsTemplate( 'backend/atsd_article_accessory_direct_buy/article/atsd_article_accessory_direct_buy_app.js' );

		// and we re done
		return;
	}







}