<?php
/*
* Plugin Name: WP Engine Cache Warmup
* Plugin URI: https://alphaparticle.com/
* Description: This plugin checks whether the cache has been cleared recently and, if so, uses your sitemap.xml file to loop through relevant URLs on your site and make sure they're added to the cache.
* Author: Alpha Particle
* Author URI: https://alphaparticle.com/
* Version: 1.0.0
* Text Domain: cache-warmup
* Domain Path: /languages
*/

namespace WPE_Cache_Warmup;

class Cache_Warmup {
	public function __construct(){
		require 'vendor/autoload.php';
		require_once plugin_dir_path( __FILE__ ) . 'classes/class-sitemap.php';
		require_once plugin_dir_path( __FILE__ ) . 'classes/class-crawler.php';

		$this->sitemap = new Sitemap();
		$this->crawler = new Crawler();

		$this->_setup_hooks();

		if( ! get_option( 'cache_last_warmed', false ) ) {
			$now = gmdate( 'Y-m-d H:i:s' );
			update_option( 'cache_last_warmed', $now);
		}

		//TO-DO: Admin notice for if this isn't a WP Engine install
		//TO-DO: Admin notice if there doesn't appear to be a sitemap
		//TO-DO: Option to specify custom sitemap URL	
	}

	public function _setup_hooks(){
		add_filter( 'cron_schedules', [ $this, 'add_five_minute_schedule' ] );

		add_action( 'cache_warmup', [ $this, 'action_cache_warmup'] );
		add_action( 'init', [ $this, 'setup_cron_job' ] );
	}

	public function setup_cron_job(){
		if ( wp_get_schedule( 'cache_warmup' ) !== '5mins' ) {
			if ( $time = wp_next_scheduled( 'cache_warmup' ) ) {
	        	wp_unschedule_event( $time, 'cache_warmup' );
			}

			$response = wp_schedule_event( time(), '5mins', 'cache_warmup' );
		}
	}

	public function add_five_minute_schedule( $schedules ) {
	    $schedules['5mins'] = array(
	        'interval' => 5 * 60,
	        'display' => __('Once every 5 minutes')
	    );

	    return $schedules;
	}

	public function action_cache_warmup() {
		$cache_last_cleared = get_option('wpe_cache_last_cleared', true);
		$cache_last_warmed  = get_option('cache_last_warmed', true);
		if($cache_last_cleared >= $cache_last_warmed) {
			$links_to_crawl = $this->sitemap->parse();

			$this->crawler->crawl( $links_to_crawl );
		}
	}
}

new Cache_Warmup();
