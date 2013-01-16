<?php

require_once( __DIR__ . '/../src/Iniliq.php' );

$content = <<<EOF
[Readme]
example = { json: true, is-it: [ good, great, awesome ] }
EOF;

$ini = ( new \Pixel418\Iniliq )->parse( $content );
print_r( $ini );

?>