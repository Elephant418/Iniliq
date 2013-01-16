<?php

require_once( __DIR__ . '/../src/Iniliq.php' );

$contents = [ ];
$contents[ ] = <<<EOF
// base.ini
[Readme]
example[name] = John Doe
example[id] = 3
EOF;
$contents[ ] = <<<EOF
// file-inheritance.ini
[Readme]
example.name = file-inheritance
EOF;

$ini = ( new \Pixel418\Iniliq )->parse( $contents );
print_r( $ini );

?>