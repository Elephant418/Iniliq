<?php

require_once( __DIR__ . '/../vendor/autoload.php' );

$contents = array( );

$contents[ 'base.ini' ] = <<<EOF
[Readme]
example[name] = John Doe
example[id] = 3
EOF;

$contents[ 'file-inheritance.ini' ] = <<<EOF
[Readme]
example.name = file-inheritance
EOF;

$result = new \Pixel418\Iniliq\Parser;
$result->parse( $contents );

include( __DIR__ . '/_output.php' );

?>