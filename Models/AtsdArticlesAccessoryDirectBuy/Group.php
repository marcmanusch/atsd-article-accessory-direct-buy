<?php

/**
 * Aquatuning Software Development - Articles Accessory Direct Buy Plugin - Model - Group
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
 * @ORM\Table(name="atsd_article_accessory_direct_buy_groups")
 * @ORM\Entity
 */

class Group extends ModelEntity
{
     
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    
    private $id;
    
    /**
     * @ORM\Column(name="article_id", type="integer", nullable=false)
     */
    
    private $article_id;
    
    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    
    private $name;
    
    /**
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    
    private $description;
    
    /**
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    
    private $image;

    /**
     * @ORM\Column(name="multiple_choice", type="integer", nullable=false)
     */
    
    private $multiple_choice;
    
 

 
    public function getId() {
         return $this->id;
    }

}

