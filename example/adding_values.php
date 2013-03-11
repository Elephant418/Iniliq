<?php

require_once( __DIR__ . '/../vendor/autoload.php' );

$contents = array( );

$contents[ 'list.ini' ] = <<<EOF
[Readme]
musketeers.name[ ] = Athos
musketeers.name[ ] = Porthos
musketeers.name[ ] = "D'Artagnan"
EOF;

$contents[ 'adding-values.ini' ] = <<<EOF
[Readme+]
musketeers.name += Aramis
EOF;

$result = new \Pixel418\Iniliq\IniParser;
$result = $result->parse( $contents );

include( __DIR__ . '/_output.php' );

?>