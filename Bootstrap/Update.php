<?php

/**
 * Aquatuning Software Development - Articles Accessory Direct Buy - Setup
 *
 * @category  Aquatuning
 * @package   Shopware\Plugins\AtsdArticleAccessoryDirectBuy
 * @copyright Copyright (c) 2013, Aquatuning GmbH
 */

namespace Shopware\AtsdArticleAccessoryDirectBuy\Bootstrap;



/**
 * Aquatuning Software Development - Articles Accessory Direct Buy - Setup
 */

class Update
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
	 * @return \Shopware\AtsdArticleAccessoryDirectBuy\Bootstrap\Update
	 */

	public function __construct( \Shopware_Components_Plugin_Bootstrap $bootstrap )
	{
		// set params
		$this->bootstrap = $bootstrap;
	}







	/**
	 * ...
	 *
	 * @return boolean
	 */

	public function install()
	{
		// install updates
		$this->update( "1.0.0" );

		// done
		return true;
	}








	/**
	 * Update our plugin if necessary.
	 *
	 * @param string   $version
	 *
	 * @return boolean
	 */

	public function update( $version )
	{
		// check current installed version
		switch ( $version )
		{
			case "1.0.0":
			case "1.0.1":
				$this->updateVersion102();
			case "1.0.2":
				$this->updateVersion103();
			case "1.0.3":
			case "1.0.4":
			case "1.0.5":
				$this->updateVersion200();
		}

		// all done
		return true;
	}






	/**
	 * Updates the plugin to 1.0.2.
	 *
	 * @return void
	 */

	public function updateVersion102()
	{
		// create the form
		$form = $this->bootstrap->Form();

		// and set the element
		$form->setElement( "boolean", "status",
			array(
				'label'       => "Aktiviert",
				'description' => "Plugin aktivieren?",
				'value'       => true
			)
		);

		// done
		return;
	}





	/**
	 * Updates the plugin to 1.0.3.
	 *
	 * @return void
	 */

	public function updateVersion103()
	{
		// create the form
		$form = $this->bootstrap->Form();

		// and set the element
		$form->setElement( "boolean", "statusSubshop",
			array(
				'label'       => "Aktiviert für Shop",
				'description' => "Plugin für diesen Shop aktivieren?",
				'value'       => true,
				'scope'       => \Shopware\Models\Config\Element::SCOPE_SHOP
			)
		);

		// done
		return;
	}





	/**
	 * ...
	 *
	 * @return void
	 */

	private function updateVersion200()
	{
		// load our main subscriber
		$this->bootstrap->subscribeEvent(
			"Enlight_Controller_Front_DispatchLoopStartup",
			"onStartDispatch"
		);

		// done
		return;
	}







}