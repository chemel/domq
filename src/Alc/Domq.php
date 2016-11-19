<?php

namespace Alc;

use Commando\Command;
use Alc\HtmlDomParserHelper;

class Domq {

	private $url;
	private $selector;
	private $function;
	private $arguments;

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
		$this->url = $command[0];
		$this->selector = $command[1];
		$this->function = $command[2];
		$this->arguments = isset($command[3]) ? array($command[3]) : array();

		// Shortcut
		if($this->function == 'attr') $this->function = 'getAttribute';

		$parser = $this->getParser( $this->url );

		if( $parser ) {

			$nodes = $parser->find($this->selector);

			if( empty($nodes) ) $this->println('[DOMQ ERROR] Your query return empty result');

			foreach( $nodes as $node ) {

			    $this->println(call_user_func_array(array($node, $this->function), $this->arguments));
			}
		}
		else {

			$this->println('[DOMQ ERROR] Parse error, url: '.$url);
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