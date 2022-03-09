# WP Engine Cache Warmup

WP Engine's caching mechanism is very useful for increasing the performance of your site. However, if you have many different caches fragmented by [User Cache Segmentation](https://wpengine.com/support/personalization-user-segmentation-page-cache) or just want to make sure that users are always getting the cached version of your pages after you deploy your code or make changes, the default setup doesn't support this.

This plugin checks whether the cache has been cleared recently and, if so, uses your `sitemap.xml` file to loop through relevant URLs on your site and make sure they're added to the cache.
