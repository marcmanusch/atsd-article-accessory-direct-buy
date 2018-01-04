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
 * 2.1.0
 * - added shopware 5.3 compatibility (sw5.3 only)
 *
 * 2.1.1
 * - added asynchronous loading of jquery plugin
 *
 * 2.1.2
 * - fixed article images
 *
 * 2.1.3
 * - shorten article names to one line with following dots
 *
 * @category  Aquatuning
 * @package   Shopware\Plugins\AtsdArticleAccessoryDirectBuy
 * @copyright Copyright (c) 2013, Aquatuning GmbH
 */

use Shopware\AtsdArticleAccessoryDirectBuy\Bootstrap;
use Shopware\AtsdArticleAccessoryDirectBuy\Subscriber;



class Shopware_Plugins_Frontend_AtsdArticleAccessoryDirectBuy_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{

    // info
    private $pluginInfo = array(
        'version'     => "2.1.3",
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
    private $pluginCapabilities = array(
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
        return $this->pluginInfo['version'];
    }
    
    
    
    /**
     * Get (nice) name for the plugin manager list.
     * 
     * @return string
     */
    
    public function getLabel()
    {
        return $this->pluginInfo['label'];
    }
    

    
    /**
     * Get full information for the plugin manager list.
     *
     * @return array
     */
    
    public function getInfo()
    {
        return $this->pluginInfo;
    } 
     

     
    /**
     * Get capabilities for the plugin manager.
     * 
     * @return array
     */
    
    public function getCapabilities()
    {
        return $this->pluginCapabilities;
    }





    /**
     * Install our plugin.
     *
     * @return array
     */

    public function install()
    {
        try
        {
            // install the plugin
            $installer = new Bootstrap\Install( $this );
            $installer->install();

            // update it to current version
            $updater = new Bootstrap\Update( $this );
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
     * @param \Enlight_Event_EventArgs   $arguments
     *
     * @return void
     */

    public function onStartDispatch( \Enlight_Event_EventArgs $arguments )
    {
        // subscribers to add
        $subscribers = array(
            new Subscriber\Controllers( $this ),
            new Subscriber\Components\Theme\Compiler( $this ),
            new Subscriber\Controllers\Backend\Article( $this, $this->get( "service_container" ) ),
            new Subscriber\Controllers\Frontend\Detail( $this, $this->get( "service_container" ) )
        );

        // loop them
        foreach( $subscribers as $subscriber )
            // and add subscriber
            $this->get( "events" )->addSubscriber( $subscriber );
    }







    /**
     * Register our custom models after initialisation.
     *
     * @return void
     */

    public function afterInit()
    {
        // register the namespace
        $this->get( "loader" )->registerNamespace(
            'Shopware\AtsdArticleAccessoryDirectBuy',
            $this->Path()
        );

        // register models
        $this->registerCustomModels();
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
        $updater = new Bootstrap\Update( $this );
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
        $uninstaller = new Bootstrap\Uninstall( $this );
        $uninstaller->uninstall();

        // done
        return true;
    }






}