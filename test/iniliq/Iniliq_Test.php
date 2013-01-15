<?php

namespace Test\Iniliq;

require_once( __DIR__ . '/../../vendor/pixel418/iniliq/iniliq/Iniliq.php' );

class Iniliq_Test extends \PHPUnit_Framework_TestCase {



	// FILE WITH SIMPLE KEY -> VALUE DATA
	public function test_parsing_a_simple_file( ) {
		$file = realpath( __DIR__ . '/../resource/simple.ini' );
		$ini = ( new \Pixel418\Iniliq )->parse( $file );
		$this->assertEquals( $ini, parse_ini_file( $file, TRUE ) );
	}

	public function test_parsing_a_simple_file_two_times( ) {
		$file = realpath( __DIR__ . '/../resource/simple.ini' );
		$files = [ $file, $file ];
		$ini = ( new \Pixel418\Iniliq )->parse( $files );
		$this->assertEquals( $ini, parse_ini_file( $file, TRUE ) );
	}



	// FILE WITH KEY -> LIST VALUES
	public function test_parsing_a_file_with_list( ) {
	}
}