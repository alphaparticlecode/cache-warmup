<?php

namespace WPE_Cache_Warmup;

use GuzzleHttp\Client;
use GuzzleHttp\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class Crawler {
	public function crawl( $links ) {
		$now = gmdate( 'Y-m-d H:i:s' );
		update_option( 'cache_last_warmed', $now);

		$client = new Client();

		// Create the requests
		$requests = function () use($client, $links) {
		    for ($i = 0; $i <= count($links) - 1; $i++) {
		        yield new Request('GET', $links[$i]);
		    }
		};

		$pool = new Pool($client, $requests(5), [
		    'concurrency' => 5,
		    'fulfilled' => function (Response $response, $index) {
		        var_dump("{$index} successfully visited");
		    },
		    'rejected' => function ($reason, $index) {
		        var_dump("{$index} failed");
		    },
		]);

		// Initiate the transfers and create a promise
		$promise = $pool->promise();

		// Force the pool of requests to complete.
		$promise->wait();
	}
}