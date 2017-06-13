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

class Install
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
		// install
		$this->installCreateDatabase();

		// return
		return true;
	}






	/**
	 * Helper method to create the needed tables.
	 *
	 * @return void
	 */
	private function installCreateDatabase()
	{
		// get entitiy manager
		$em = Shopware()->Models();

		// get doctrine schema tool
		$tool = new \Doctrine\ORM\Tools\SchemaTool( $em );

		// get models to load
		$models = array(
			$em->getClassMetadata( 'Shopware\CustomModels\AtsdArticlesAccessoryDirectBuy\Group' ),
			$em->getClassMetadata( 'Shopware\CustomModels\AtsdArticlesAccessoryDirectBuy\Article' )
		);

		// create schema
		$tool->createSchema( $models );



		// debug tables
		$query = "ALTER TABLE `atsd_article_accessory_direct_buy_articles` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci";
		Shopware()->Db()->query( $query );

		$query = "ALTER TABLE `atsd_article_accessory_direct_buy_articles` CHANGE  `ordernumber`  `ordernumber` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,CHANGE  `optionname`  `optionname` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		Shopware()->Db()->query( $query );

		$query = "ALTER TABLE `atsd_article_accessory_direct_buy_articles` ADD UNIQUE KEY `accessory_group_id` (`accessory_group_id`,`ordernumber`)";
		Shopware()->Db()->query( $query );

		$query = "ALTER TABLE `atsd_article_accessory_direct_buy_groups` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci";
		Shopware()->Db()->query( $query );

		$query = "ALTER TABLE `atsd_article_accessory_direct_buy_groups` CHANGE  `name`  `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,CHANGE  `description`  `description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,CHANGE  `image`  `image` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		Shopware()->Db()->query( $query );
	}







}