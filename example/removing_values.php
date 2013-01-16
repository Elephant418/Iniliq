<?php

require_once( __DIR__ . '/../src/Iniliq.php' );

$contents = [ ];
$contents[ ] = <<<EOF
// list.ini
[Readme]
musketeers.name[ ] = Athos
musketeers.name[ ] = Porthos
musketeers.name[ ] = "D'Artagnan"
EOF;
$contents[ ] = <<<EOF
// removing-values.ini
[Readme]
musketeers.name -= "[ D'Artagnan ]"
EOF;

$ini = ( new \Pixel418\Iniliq )->parse( $contents );
print_r( $ini );

?>