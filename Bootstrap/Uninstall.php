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

class Uninstall
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
	 * @return \Shopware\AtsdArticleAccessoryDirectBuy\Bootstrap\Uninstall
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

	public function uninstall()
	{
		// uninstall
		$this->uninstallDatabase();

		// return
		return true;
	}







	/**
	 * ...
	 *
	 * @return void
	 */

	public function uninstallDatabase()
	{
		// get entity manager
		$em = Shopware()->Models();

		// debug
		$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping( "enum", "string" );

		// get our schema tool
		$tool = new \Doctrine\ORM\Tools\SchemaTool( $em );

		// all our custom models
		$classes = array(
			$em->getClassMetadata( 'Shopware\CustomModels\AtsdArticlesAccessoryDirectBuy\Group' ),
			$em->getClassMetadata( 'Shopware\CustomModels\AtsdArticlesAccessoryDirectBuy\Article' )
		);

		// and remove them
		$tool->dropSchema( $classes );

		// done
		return;
	}









}