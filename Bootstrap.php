<?php

/**
 * Aquatuning Software Development - Articles Accessory Direct Buy Plugin - Bootstrap
 *
 * 1.0.0
 * - initial release
 *
 * 1.0.1
 * - rewritten template
 *
 * 1.0.2
 * - added configuration to enable plugin for a shop
 *
 * 1.0.3
 * - debug for 1.0.2
 *
 * 1.0.4
 * - added shopware 4.3.x compability
 *
 * 1.0.5
 * - added snippet to template
 *
 * 2.0.0
 * - added shopware 5 compability (sw5 only)
 * - added subscriber interface
 *
 * 2.0.1
 * - added hover popup for every article
 *
 * 2.0.2
 * - removed legacy event listener
 *
 * 2.0.3
 * - moved the template into another smarty block
 * - always include the template dir and hide the html block if we have no accessory articles
 *
 * 2.0.4
 * - add check if images exists for the accessory articles
 *
 * 2.0.5
 * - split onDispatch() method to load subscribers depending on request module
 *
 * 2.0.6
 * - revoked 2.0.5
 *
 * 2.0.7
 * - added default snippet as group name
 *
 * @category  Aquatuning
 * @package   Shopware\Plugins\AtsdArticleAccessoryDirectBuy
 * @copyright Copyright (c) 2013, Aquatuning GmbH
 */

class Shopware_Plugins_Frontend_AtsdArticleAccessoryDirectBuy_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{

    // info
    private $plugin_info = array(
        'version'     => "2.0.7",
        'label'       => "ATSD - Artikel Direkt-Kauf",
        'description' => "ATSD - Artikel Direkt-Kauf",
        'supplier'    => "Aquatuning GmbH",
        'autor'       => "Aquatuning GmbH",
        'support'     => "Aquatuning GmbH",
        'copyright'   => "Aquatuning GmbH",
        'link'        => 'http://www.aquatuning.de',
        'source'      => null,
        'changes'     => null,
        'license'     => null,
        'revision'    => null
    );
    
    // get capabilities
    private $plugin_capabilities = array(
        'install' => true,
        'update'  => true,
        'enable'  => true
    );

    // invalidate these caches
    private $invalidateCacheArray = array(
        "proxy",
        "frontend",
        "backend",
        "theme",
        "template",
        "config"
    );



    /**
     * Returns the current version of the plugin.
     * 
     * @return string
     */
    
    public function getVersion()
    {
        return $this->plugin_info['version'];
    }
    
    
    
    /**
     * Get (nice) name for the plugin manager list.
     * 
     * @return string
     */
    
    public function getLabel()
    {
        return $this->plugin_info['label'];
    }
    

    
    /**
     * Get full information for the plugin manager list.
     *
     * @return array
     */
    
    public function getInfo()
    {
        return $this->plugin_info;
    } 
     

     
    /**
     * Get capabilities for the plugin manager.
     * 
     * @return array
     */
    
    public function getCapabilities()
    {
        return $this->plugin_capabilities;
    }





    /**
     * Install our plugin.
     *
     * @return bool
     */

    public function install()
    {
        try
        {
            // install the plugin
            $installer = new \Shopware\AtsdArticleAccessoryDirectBuy\Bootstrap\Install( $this );
            $installer->install();

            // update it to current version
            $updater = new \Shopware\AtsdArticleAccessoryDirectBuy\Bootstrap\Update( $this );
            $updater->install();

            // fertig
            return array(
                'success'         => true,
                'invalidateCache' => $this->invalidateCacheArray
            );
        }
        catch ( Exception $e )
        {
            return array(
                'success' => false,
                'message' => $e->getMessage()
            );
        }
    }






    /**
     * ...
     *
     * @param Enlight_Event_EventArgs   $args
     *
     * @return string
     */

    public function onGetControllerPathBackend( Enlight_Event_EventArgs $args )
    {
        // load our template path
        Shopware()->Template()->addTemplateDir(
            $this->Path() . "Views/"
        );

        // return our controller
        return $this->Path(). "Controllers/Backend/AtsdArticleAccessoryDirectBuy.php";
    }







    /**
     * ...
     *
     * @param \Enlight_Event_EventArgs   $arguments
     *
     * @return void
     */

    public function onStartDispatch( \Enlight_Event_EventArgs $arguments )
    {
        // subscribers to add
        $subscribers = array(
            new \Shopware\AtsdArticleAccessoryDirectBuy\Subscriber\Controllers\Backend\Article( $this, $this->get( "service_container" ) ),
            new \Shopware\AtsdArticleAccessoryDirectBuy\Subscriber\Components\Theme\Compiler( $this ),
            new \Shopware\AtsdArticleAccessoryDirectBuy\Subscriber\Controllers\Frontend\Detail( $this, $this->get( "service_container" ) ),
            new \Shopware\AtsdArticleAccessoryDirectBuy\Subscriber\Controllers\Frontend\Checkout( $this, $this->get( "service_container" ) )
        );

        // loop them
        foreach( $subscribers as $subscriber )
            // and add subscriber
            $this->Application()->Events()->addSubscriber( $subscriber );
    }







    /**
     * Register our custom models after initialisation.
     *
     * @return void
     */

    public function afterInit()
    {
        // register the namespace
        $this->Application()->Loader()->registerNamespace(
            'Shopware\AtsdArticleAccessoryDirectBuy',
            $this->Path()
        );

        // register models
        $this->registerCustomModels();

        // done
        return;
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
        // update it to current version
        $updater = new \Shopware\AtsdArticleAccessoryDirectBuy\Bootstrap\Update( $this );
        $updater->update( $version );

        // all done
        return true;
    }





    /**
     * Uninstall our plugin.
     *
     * @return boolean
     */

    public function uninstall()
    {
        // update it to current version
        $uninstaller = new \Shopware\AtsdArticleAccessoryDirectBuy\Bootstrap\Uninstall( $this );
        $uninstaller->uninstall();

        // done
        return true;
    }






}