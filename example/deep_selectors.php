<?php

require_once( __DIR__ . '/../src/Iniliq.php' );

$content = <<<EOF
[Readme]
example.selectors.deep = nice
EOF;

$ini = ( new \Pixel418\Iniliq\Parser )->parse( $content );
print_r( $ini );

?>