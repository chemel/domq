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

if( file_exists($url) ) {

    // Parse from local file
    $parser = $helper->getHtmlDomParser($helper->convertEncodingToUTF8(file_get_contents($url)));
}
else {

    $parser = $helper->parse($url);
}

if(!$parser) exit('[DOMQ ERROR] Parse error'."\n");

$nodes = $parser->find($selector);

if( empty($nodes) ) exit('[DOMQ ERROR] Your query return empty result'."\n");

foreach( $nodes as $node ) {

    echo call_user_func_array(array($node, $function), $arguments), "\n";
}

$helper->clear();
