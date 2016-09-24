<?php

/**
 * Aquatuning Software Development - Articles Accessory Direct Buy - Subscriber
 *
 * @category  Aquatuning
 * @package   Shopware\Plugins\AtsdArticleAccessoryDirectBuy
 * @copyright Copyright (c) 2013, Aquatuning GmbH
 */

namespace Shopware\AtsdArticleAccessoryDirectBuy\Subscriber\Components\Theme;



/**
 * Aquatuning Software Development - Articles Accessory Direct Buy - Subscriber
 */

class Compiler implements \Enlight\Event\SubscriberInterface
{

	/**
	 * Main bootstrap object.
	 *
	 * @var \Shopware_Components_Plugin_Bootstrap
	 */

	protected $bootstrap;





	/**
	 * ...
	 *
	 * @param \Shopware_Components_Plugin_Bootstrap   $bootstrap
	 *
	 * @return \Shopware\AtsdArticleAccessoryDirectBuy\Subscriber\Components\Theme\Compiler
	 */

	public function __construct( \Shopware_Components_Plugin_Bootstrap $bootstrap )
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
			'Theme_Compiler_Collect_Plugin_Less'       => 'addLessFiles',
			'Theme_Compiler_Collect_Plugin_Javascript' => 'addJavascriptFiles'
		);
	}






	/**
	 * ...
	 *
	 * @param \Enlight_Event_EventArgs   $arguments
	 *
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */

	public function addLessFiles( \Enlight_Event_EventArgs $arguments )
	{
		// create less array
		$less = new \Shopware\Components\Theme\LessDefinition(
			array(),
			array(
				$this->bootstrap->Path() . 'Views/frontend/_public/src/less/all.less'
			),
			$this->bootstrap->Path()
		);

		// return it
		return new \Doctrine\Common\Collections\ArrayCollection( array( $less ) );
	}





	/**
	 * ...
	 *
	 * @param \Enlight_Event_EventArgs   $arguments
	 *
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */

	public function addJavascriptFiles( \Enlight_Event_EventArgs $arguments )
	{
		// all js files here
		$files = array(
			$this->bootstrap->Path() . "Views/frontend/_public/src/js/jquery.atsd-accessory-direct-buy.js"
		);

		// return them
		return new \Doctrine\Common\Collections\ArrayCollection( $files );
	}






}