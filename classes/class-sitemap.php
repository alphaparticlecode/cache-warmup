<?php

namespace WPE_Cache_Warmup;

class Sitemap {
	public function __construct() {
		$this->urls = [];
	}

	public function parse( $sitemap_url = null ) {
		if( ! $sitemap_url ) {
			$sitemap_url = home_url( 'sitemap.xml' );

			$response = wp_remote_get( $sitemap_url );
			$code = wp_remote_retrieve_response_code( $response );

			if( ! $code ) {
				$sitemap_url = home_url( 'sitemap_index.xml' );
			}
		}

		$this->parsePage( $sitemap_url );

		return $this->urls;
	}

	public function parsePage( $sitemap_page ) {
		$DomDocument = new \DOMDocument();
		$DomDocument->preserveWhiteSpace = false;
		$DomDocument->load( $sitemap_page );
		$DomNodeList = $DomDocument->getElementsByTagName('loc');

		foreach($DomNodeList as $url) {
		    // If XML is in the path, the link is to another sitemap page
		    // and needs to be parsed recursively
		    if( strpos( $url->nodeValue, 'xml' ) !== false ) {
		    	$this->parsePage( $url->nodeValue );
		    } elseif( strpos( $url->nodeValue, '.png' ) !== false || strpos( $url->nodeValue, '.jpg' ) !== false || strpos( $url->nodeValue, '.gif' ) !== false ) {
		    	// Don't parse images
		    	continue;
		    } else {
		    	$this->urls[] = $url->nodeValue;
		    }
		}
	}
}