#!/usr/bin/php
<?php

require __DIR__.'/vendor/autoload.php';

use Commando\Command;
use Alc\HtmlDomParserHelper;

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

// Scrappe
$helper = new HtmlDomParserHelper();
$parser = $helper->parse($url);

$nodes = $parser->find($selector);

if( !empty($nodes) ) {

	foreach( $nodes as $node ) {

		echo call_user_func_array(array($node, $function), $arguments), "\n";
	}
}

$helper->clear();