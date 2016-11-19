<?php

namespace Alc;

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
		    ->require()
		    ->describedAs('function');

		$command->option()
		    ->describedAs('arguments');

		// Arguments
		$url = $command[0];
		$selector = $command[1];
		$function = $command[2];
		$arguments = isset($command[3]) ? array($command[3]) : array();

		// Shortcut
		if($function == 'attr') $function = 'getAttribute';

		if( stripos($url, 'list=') === 0 ) {

			$urls = explode("\n", file_get_contents(substr($url, 5)));
		}
		else {

			$urls = array( $url );
		}

		foreach( $urls as $url ) {

			$url = trim($url);

			if( empty($url) ) continue;

			$parser = $this->getParser( $url );

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

	public function getParser( $url ) {

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