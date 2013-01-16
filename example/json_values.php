<?php

require_once( __DIR__ . '/../vendor/autoload.php' );

$content = <<<EOF
[Readme]
example = { json: true, is-it: [ good, great, awesome ] }
EOF;

$result = ( new \Pixel418\Iniliq\Parser )->parse( $content );

include( __DIR__ . '/_output.php' );

?>