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

class Detail implements \Enlight\Event\SubscriberInterface
{

	/**
	 * Main bootstrap object.
	 *
	 * @var \Shopware_Components_Plugin_Bootstrap
	 */

	protected $bootstrap;



	/**
	 * Shopware DB component.
	 *
	 * @var \Enlight_Components_Db_Adapter_Pdo_Mysql
	 */

	protected $db;



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
	 * @return \Shopware\AtsdArticleAccessoryDirectBuy\Subscriber\Controllers\Frontend\Detail
	 */

	public function __construct( \Shopware_Components_Plugin_Bootstrap $bootstrap, \Shopware\Components\DependencyInjection\Container $container )
	{
		// set params
		$this->bootstrap = $bootstrap;
		$this->container = $container;

		// set everything else
		$this->db        = $this->container->get( "shopware.db" );
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
			'Enlight_Controller_Action_PostDispatch_Frontend_Detail' => "onPostDispatchDetail",
		);
	}









	/**
	 * ...
	 *
	 * @param \Enlight_Event_EventArgs   $args
	 *
	 * @return void
	 */

	public function onPostDispatchDetail( \Enlight_Event_EventArgs $args )
	{
		// get the controller
		/* @var $controller \Shopware_Controllers_Frontend_Detail */
		$controller = $args->get( "subject" );

		// get parameters
		$request    = $controller->Request();
		$response   = $controller->Response();
		$view       = $controller->View();

		// valid request?
		if ( !$request->isDispatched() || $response->isException() || !$view->hasTemplate() || $request->getModuleName() != "frontend" )
			// abort
			return;

		// disabled by config?
		if ( ( $this->bootstrap->Config()->get( "status" ) == false ) or ( $this->bootstrap->Config()->get( "statusSubshop" ) == false ) )
			// disabled
			return;



		// get the article
		$article = $view->getAssign( "sArticle" );

		// no product?!
		if ( !is_array( $article ) )
			// nothing to do
			return;



		// get the groups
		$groups = $this->getAccessories( $article["articleID"] );



        /*
		// only if we have even have groups
		if ( count( $groups ) == 0 )
			// dont do anything
			return;
        */



		// we have to set default shopware accessories or the block wont be displayed
		// $article['sAccessories'] = true;

		// re-assign the article
		// $view->assign( "sArticle", $article );



		// assign them to the view
		$view->assign( "atsdAccessories", $groups );

		// add our template dir
		$view->addTemplateDir( $this->bootstrap->Path() . "Views/" );

		// and we re done
		return;
	}







	/**
	 * ...
	 *
	 * @param integer   $articleId
	 *
	 * @return array
	 */

	private function getAccessories( $articleId )
	{
		// get services
		/* @var $contextService \Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface */
		$contextService     = $this->container->get( "shopware_storefront.context_service" );

		/* @var $listProductService \Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface */
		$listProductService = $this->container->get( "shopware_storefront.list_product_service" );

		/* @var $mediaService \Shopware\Bundle\MediaBundle\MediaService */
		$mediaService       = $this->container->get( "shopware_media.media_service" );



		// groups here
		$groups  = array();

		// order numbers for the product list service here
		$numbers = array();



		// read everything
		$query = "
		    SELECT a.id AS groupId, a.name AS groupName, a.multiple_choice AS groupMultiple,
		        b.id AS articleId, b.ordernumber AS articleNumber, b.quantity AS articleQuantity
		    FROM atsd_article_accessory_direct_buy_groups a
		        LEFT JOIN atsd_article_accessory_direct_buy_articles b
		            ON a.id = b.accessory_group_id
		    WHERE a.article_id = :articleId
		    ORDER BY a.name ASC, b.ordernumber ASC
		";
		$res = $this->db->fetchAll( $query, array( 'articleId' => (integer) $articleId ) );

		// loop db result
		foreach ( $res as $aktu )
		{
			// get group id
			$groupId = (integer) $aktu['groupId'];

			// group not set yet
			if ( !isset( $groups[$groupId] ) )
				// set it up
				$groups[$groupId] = array(
					'id'       => $groupId,
					'name'     => $aktu['groupName'],
					'multiple' => (boolean) $aktu['groupMultiple'],
					'articles' => array()
				);

			// add current article
			array_push(
				$groups[$groupId]['articles'],
				array(
					'id'       => $aktu['articleId'],
					'number'   => $aktu['articleNumber'],
					'quantity' => $aktu['articleQuantity']
				)
			);

			// add order number
			array_push(
				$numbers,
				$aktu['articleNumber']
			);
		}

		// unique numbers
		$numbers = array_unique( $numbers );

		// do we even have anything?
		if ( count( $numbers ) == 0 )
			// nothing to do
			return array();



		// now get details for every accessory article
		$products = $listProductService->getList(
			$numbers,
			$contextService->getProductContext()
		);



		// loop every group
		foreach ( $groups as $groupKey => $group )
		{
			// loop every article
			foreach ( $group['articles'] as $articleKey => $article )
			{
				// do we have a list product for it?
				if ( !isset( $products[$article['number']] ) )
				{
					// remove it
					unset( $group['articles'][$articleKey] );
					// next
					continue;
				}

				// add the product
				$group['articles'][$articleKey]['product'] = $products[$article['number']];



				// get formatted price
				$price = Shopware()->Modules()->Articles()->sFormatPrice( $products[$article['number']]->getCheapestPrice()->getCalculatedPrice() );



                // get the cover
                $cover = $products[$article['number']]->getCover();

                // does not have an image
                if ( !$cover instanceof \Shopware\Bundle\StoreFrontBundle\Struct\Media )
                {
                    // remove it
                    unset( $group['articles'][$articleKey] );
                    // next
                    continue;
                }

                // get the image
                $image = $cover->getThumbnail( 0 )->getSource();



				// add as attribute
				$products[$article['number']]->addAttribute(
					"atsd_accessory",
					new \Shopware\Bundle\StoreFrontBundle\Struct\Attribute(
						array(
							'formattedPrice' => $price,
							'image'          => $mediaService->getUrl( $image )
						)
					)
				);
			}

			// reset key
			$group['articles'] = array_values( $group['articles'] );

			// set it back
			$groups[$groupKey] = $group;

			// any articles left?
			if ( count( $group['articles'] ) == 0 )
				// remove it
				unset( $groups[$groupKey] );
		}

		// reset keys
		$groups = array_values( $groups );



		// return the groups
		return $groups;
	}








}