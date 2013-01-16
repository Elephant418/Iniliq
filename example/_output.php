<?php

if ( ! isset( $contents ) ) {
	$contents = \UArray::convert_to_array( $content );
}

echo PHP_EOL;
echo 'Files' . PHP_EOL;
echo '---------' . PHP_EOL;
foreach ( $contents as $name => $content ) {
	if ( ! is_numeric( $name ) ) {
		echo '; ' . $name . PHP_EOL;
	}
	echo $content . PHP_EOL;
	echo PHP_EOL;
}
echo PHP_EOL;
echo 'Result parsing' . PHP_EOL;
echo '---------' . PHP_EOL;
print_r( $result );