<?php

require_once( __DIR__ . '/../vendor/autoload.php' );

$content = <<<EOF
[Readme]
example.selectors.deep = nice
EOF;

$result = new \Pixel418\Iniliq\Parser;
$result = $result->parse( $content );

include( __DIR__ . '/_output.php' );

?>