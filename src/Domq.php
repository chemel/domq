<?php

namespace Alc\Domq;

use Commando\Command;
use Alc\HtmlDomParserHelper;

class Domq {

	public function run() {

		$command = new Command();

		// Define first option
		$command->option()
		    ->require()
		    ->describedAs('url');

		$command->option()
		    ->require()
		    ->describedAs('selector');

		$command->option()
		    ->describedAs('function')
		    ->default('innertext');

		$command->option()
		    ->describedAs('arguments');

		// Arguments
		$url = $command[0];
		$selector = $command[1];
		$function = $command[2];
		$arguments = isset($command[3]) ? array($command[3]) : array();

		// Shortcut
		if($function == 'attr') $function = 'getAttribute';

		if( $url == 'stdin' ) { // Piping xml file

			$xmlContent = file_get_contents("php://stdin");

			$urls = array('-');
		}
		elseif( $url == 'urls=-' ) { // Pipping url file list

			$urls = explode("\n", file_get_contents("php://stdin"));
		}
		elseif( stripos($url, 'urls=') === 0 ) { // Get urls from file

			$urls = explode("\n", file_get_contents(substr($url, 5)));
		}
		else { // Défault single url

			$urls = array( $url );
		}

		foreach( $urls as $url ) {

			$url = trim($url);

			if( empty($url) ) continue;

			if( $url == '-' && isset($xmlContent) ) {

				$helper = new HtmlDomParserHelper();
				$parser = $helper->getHtmlDomParser($helper->convertEncodingToUTF8($xmlContent));
			}
			else {
				$parser = $this->getParser( $url );
			}

			if( $parser ) {

				$nodes = $parser->find($selector);

				if( empty($nodes) ) $this->println('[DOMQ ERROR] Your query return empty result');

				foreach( $nodes as $node ) {

				    $this->println(call_user_func_array(array($node, $function), $arguments));
				}
			}
			else {

				$this->println('[DOMQ ERROR] Parse error, url: '.$url);
			}
		}
	}

	private function getParser( $url ) {

		$helper = new HtmlDomParserHelper();

		if( file_exists($url) ) {

		    // Parse from local file
		    $parser = $helper->getHtmlDomParser($helper->convertEncodingToUTF8(file_get_contents($url)));
		}
		else {

		    $parser = $helper->parse($url);
		}

		return $parser;
	}

	private function println( $text ) {

		echo $text, "\n";
	}
}