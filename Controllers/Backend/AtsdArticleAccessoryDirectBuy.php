<?php

/**
 * Aquatuning Software Development - Articles Accessory Direct Buy Plugin - Backend Controller
 *
 * @category  Aquatuning
 * @package   Shopware\Plugins\AtsdArticleAccessoryDirectBuy
 * @copyright Copyright (c) 2013, Aquatuning GmbH
 */

class Shopware_Controllers_Backend_AtsdArticleAccessoryDirectBuy extends Shopware_Controllers_Backend_ExtJs
{

    /**
     * Function to get all accessory-groups and the associated articles.
     *
     * @return void
     */

	public function getAccessoryGroupsAction()
    {
        try
        {
            $start = (int) $this->Request()->get('start');
            $limit = (int) $this->Request()->get('limit');
            $sort = (array)$this->Request()->getParam('sort', array());
            $articleId = (int) $this->Request()->get('articleId');

            if(!empty($sort))
            {
                $direction = $sort[0]["direction"];
                $property = $sort[0]["property"];
                $sqlOrder = "ORDER BY {$property} {$direction}";
            }
            else
            {
                $sqlOrder = "";
            }
            
            $sql = "SELECT acag.*
                    FROM atsd_article_accessory_direct_buy_groups acag
                    WHERE article_id = :articleId
                    {$sqlOrder}
                    LIMIT :start, :limit
            ";
            $prepared = Shopware()->Db()->prepare($sql);
            $prepared->bindParam(':articleId', $articleId, PDO::PARAM_INT);
            $prepared->bindParam(':start', $start, PDO::PARAM_INT);
            $prepared->bindParam(':limit', $limit, PDO::PARAM_INT);
            $prepared->execute();

            $groups = $prepared->fetchAll();

            //Get all articles to the groups
            foreach ($groups as &$group)
            {
                $sql= "SELECT acaa.*, a.name
                    FROM atsd_article_accessory_direct_buy_articles acaa
                    LEFT JOIN s_articles_details ad ON acaa.ordernumber = ad.ordernumber
                    LEFT JOIN s_articles a ON ad.articleID = a.id
                    WHERE accessory_group_id = ?";
                $group['articles'] = Shopware()->Db()->fetchAll($sql, array($group["id"]));
                $group["count"] = count($group["articles"]);
            }

           $this->View()->assign(array("success"=>true, "data"=>$groups));
        }
        catch(Exception $e)
        {
            $this->View()->assign(array("success"=>false, "errorMsg"=>$e->getMessage()));
        }
    }





    /**
     * Function to create an accessory-group.
     *
     * @return void
     */

    public function createAccessoryGroupAction()
    {
        try
        {
            $name = $this->Request()->get("name");
            $articleId = $this->Request()->get("article_id");
			$multipleChoice = $this->Request()->get("multiple_choice");

            Shopware()->Db()->query(
                "INSERT INTO atsd_article_accessory_direct_buy_groups (name, article_id, multiple_choice) VALUES (?, ?, ?)",
                array($name, $articleId, $multipleChoice)
            );

            $this->View()->assign(array("success"=>true, "data"=>$name));
        }
        catch (Exception $e)
        {
            $this->View()->assign(array("success"=>false, "errorMsg"=>$e->getMessage()));
        }
    }





    /**
     * Function to edit an accessory-article.
     *
     * @return void
     */

    public function updateGroupArticleAction()
    {
        try
        {
            $params = $this->Request()->getParams();
            $optionName = $params["optionname"];
            $orderNumber = $params["ordernumber"];
            $accessoryGroupId = $params["accessory_group_id"];
			
			$quantity = $params["quantity"];

            $sql= "UPDATE atsd_article_accessory_direct_buy_articles
                SET optionname = ?, quantity = ?
                WHERE accessory_group_id = ?
                AND ordernumber = ?";
            Shopware()->Db()->query($sql, array($optionName, $quantity, $accessoryGroupId, $orderNumber));

            $this->View()->assign(array("success"=>true));

        }
        catch (Exception $e)
        {
            $this->View()->assign(array("success"=>false, "errorMsg"=>$e->getMessage()));
        }
    }





    /**
     * Function to edit an accessory-group.
     *
     * @return void
     */

    public function updateAccessoryGroupAction()
    {
        try
        {
            $params = $this->Request()->getParams();
            $articles = $params["articles"];

            Shopware()->Db()->query(
                "UPDATE atsd_article_accessory_direct_buy_groups SET name=?, description=?, multiple_choice=? WHERE id=?",
                array($params["name"], $params["description"], $params["multiple_choice"], $params["id"])
            );

            foreach($articles as $article)
            {
				
				$quantity = $article['quantity'];

                $sql= "INSERT IGNORE INTO atsd_article_accessory_direct_buy_articles (ordernumber, quantity, accessory_group_id) VALUES(?, ?, ?)";
                Shopware()->Db()->query($sql, array($article["ordernumber"], $quantity, $params["id"]));
            }

            $this->View()->assign(array("success"=>true, 'lastInsert'=>Shopware()->Db()->lastInsertId()));
            
        }
        catch (Exception $e)
        {
            $this->View()->assign(array("success"=>false, "errorMsg"=>$e->getMessage()));
        }
    }





    /**
     * Function to delete one or multiple accessory-groups.
     *
     * @return void
     */

    public function deleteGroupsAction()
    {
        try
        {
            $params = $this->Request()->getParams();

            unset($params['module']);
            unset($params['controller']);
            unset($params['action']);
            unset($params['_dc']);

            if($params[0])
            {
                foreach($params as $values)
                {
                    $this->deleteGroup($values);
                }

            }
            else
            {
                $this->deleteGroup($params);
            }

            $this->View()->assign(array('success'=>true));
        }
        catch(Exception $e)
        {
            $this->View()->assign(array('success'=>false, 'errorMsg'=>$e->getMessage()));
        }
    }





    /**
     * Function to remove a single accessory-article.
     *
     * @return void
     */

    public function deleteGroupArticleAction()
    {
        $params = $this->Request()->getParams();

        try
        {
            Shopware()->Db()->query(
                "DELETE FROM atsd_article_accessory_direct_buy_articles WHERE ordernumber=? AND accessory_group_id =?",
                array($params["ordernumber"], $params["accessory_group_id"])
            );

            $this->View()->assign(array('success'=>true));
        }
        catch(Exception $e)
        {
            $this->View()->assign(array('success'=>false, 'errorMsg'=>$e->getMessage()));
        }
    }





    /**
     * Helper function to delete one or multiple accessory-groups and the articles,
     * which belong to the group.
     *
     * @param array   $array
     *
     * @return void
     */

    private function deleteGroup($array)
    {
        Shopware()->Db()->query(
            "DELETE FROM atsd_article_accessory_direct_buy_groups WHERE id=?",
            array($array["id"])
        );

        Shopware()->Db()->query(
            "DELETE FROM atsd_article_accessory_direct_buy_articles WHERE accessory_group_id=?",
            array($array["id"])
        );
    }



}
