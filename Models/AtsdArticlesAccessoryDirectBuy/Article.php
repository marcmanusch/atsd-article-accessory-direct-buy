<?php

/**
 * Aquatuning Software Development - Articles Accessory Direct Buy Plugin - Model - Article
 *
 * @category  Aquatuning
 * @package   Shopware\Plugins\AtsdArticlesAccessoryDirectBuy
 * @copyright Copyright (c) 2013, Aquatuning GmbH
 */

// namespace
namespace Shopware\CustomModels\AtsdArticlesAccessoryDirectBuy;

// use
use Shopware\Components\Model\ModelEntity;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;


 
/**
 * @ORM\Table(name="atsd_article_accessory_direct_buy_articles")
 * @ORM\Entity
 */ 

class Article extends ModelEntity
{
     
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    
    private $id;
    
    /**
     * @ORM\Column(name="accessory_group_id", type="integer", nullable=false)
     */
    
    private $accessory_group_id;
    
    /**
     * @ORM\Column(name="ordernumber", type="string", length=255, nullable=false)
     */
    
    private $ordernumber;
    
    /**
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    
    private $quantity;
    
    /**
     * @ORM\Column(name="optionname", type="string", length=255, nullable=false)
     */
    
    private $optionname;

  

 
    public function getId() {
         return $this->id;
    }

}

