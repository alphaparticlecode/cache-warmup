# WP Engine Cache Warmup

This plugin checks whether the cache has been cleared recently and, if so, uses your `sitemap.xml` file to loop through relevant URLs on your site and make sure they're added to the cache.

WP Engine's caching mechanism is very useful for increasing the performance of your site. However, in many cases, a site will use many different caches fragmented by [User Cache Segmentation](https://wpengine.com/support/personalization-user-segmentation-page-cache) or want to ensure that users are always getting the cached version of your pages after you either deploy your code or clear the cache. In this case, the WP Engine cache doesn't always deliver cache hits consistently. With this plugin installed, you can be sure you're always serving the most recent versions of pages, cached, to your users.


**Note: For best results, make sure you have [WP Engine's Alternative Cron](https://wpengine.com/support/wp-cron-wordpress-scheduling/) enabled as this plugin checks if it needs to be run every 5 minutes, which is more often than the standard wp-cron runs on most sites.**
