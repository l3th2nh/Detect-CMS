<?php

class Drupal extends DetectCMS {

	public $methods = array(
		"changelog",
		"generator_header",
		"generator_meta",
		"node_css",
	);

	/**
	 * See if CHANGELOG.TXT exists, and check for Drupal
	 * @param  [string] $url
	 * @return [boolean]
	 */
	public function changelog($url) {

		if($data = $this->fetch($url."/CHANGELOG.txt")) {

			/**
			 * Changelog always starts from the second line
			 */
			$lines = explode(PHP_EOL, $data);

			return strpos($lines[1], "Drupal") !== FALSE;
		}

		return FALSE;

	}

	/**
	 * Check for Generator header
	 * @return [boolean]
	 */
	public function generator_header() {

		if(is_array($this->home_headers)) {

			foreach($this->home_headers as $line) {

				if(strpos($line, "X-Generator") !== FALSE) {

					return strpos($line, "Drupal") !== FALSE;

				}

			}

		}

		return FALSE;

	}

	/**
	 * Check meta tags for generator
	 * @return [boolean]
	 */
	public function generator_meta() {

		if($this->home_html) {

			require_once(dirname(__FILE__)."/../thirdparty/simple_html_dom.php");

			if($html = str_get_html($this->home_html)) {

				if($meta = $html->find("meta[name='generator']",0)) {

					return strpos($meta->content, "Drupal") !== FALSE;

				}

			}

		}

		return FALSE;

	}

	/**
	 * Check modules/node/node.css content
	 * @param  [string] $url
	 * @return [boolean]
	 */
	public function node_css($url) {

		if($data = $this->fetch($url."/modules/node/node.css")) {

			/**
			 * Second line always has .node-published css
			 */
			$lines = explode(PHP_EOL, $data);

			return strpos($lines[1], ".node-published") !== FALSE;
		}

		return FALSE;

	}

}