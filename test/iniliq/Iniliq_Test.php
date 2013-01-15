<?php

namespace Test\Iniliq;

require_once( __DIR__ . '/../../vendor/pixel418/iniliq/iniliq/Iniliq.php' );

class Iniliq_Test extends \PHPUnit_Framework_TestCase {



	// FILE WITH SIMPLE KEY -> VALUE DATA
	public function test_parsing_a_simple_file( ) {
		$file = realpath( __DIR__ . '/../resource/simple.ini' );
		$ini  = ( new \Pixel418\Iniliq )->parse( $file );
		$this->assertEquals( $ini, parse_ini_file( $file, TRUE ) );
	}

	public function test_parsing_a_simple_file_two_times( ) {
		$file  = realpath( __DIR__ . '/../resource/simple.ini' );
		$files = [ $file, $file ];
		$ini   = ( new \Pixel418\Iniliq )->parse( $files );
		$this->assertEquals( $ini, parse_ini_file( $file, TRUE ) );
	}



	// FILE WITH KEY -> LIST VALUES
	public function test_parsing_a_file_with_list( ) {
		$file = realpath( __DIR__ . '/../resource/list.ini' );
		$ini  = ( new \Pixel418\Iniliq )->parse( $file );
		$this->assertEquals( $ini, parse_ini_file( $file, TRUE ) );
	}

	public function test_parsing_a_file_with_list_two_times( ) {
		$file  = realpath( __DIR__ . '/../resource/list.ini' );
		$files = [ $file, $file ];
		$ini   = ( new \Pixel418\Iniliq )->parse( $files );
		$this->assertEquals( $ini, parse_ini_file( $file, TRUE ) );
	}



	// FILE ADDING LIST VALUES
	public function test_parsing_a_file_with_adding_list( ) {
		$file = realpath( __DIR__ . '/../resource/list-add.ini' );
		$ini  = ( new \Pixel418\Iniliq )->parse( $file );
		$this->assertEquals( $ini, [ 'extensions' => [ 'Extension3' ] ] );
	}

	public function test_parsing_a_file_with_adding_list_to_himself( ) {
		$file  = realpath( __DIR__ . '/../resource/list-add.ini' );
		$files = [ $file, $file ];
		$ini   = ( new \Pixel418\Iniliq )->parse( $files );
		$this->assertEquals( $ini, [ 'extensions' => [ 'Extension3', 'Extension3' ] ] );
	}

	public function test_parsing_a_file_with_adding_list_to_another_one( ) {
		$files    = [ ];
		$files[ ] = realpath( __DIR__ . '/../resource/list.ini' );
		$files[ ] = realpath( __DIR__ . '/../resource/list-add.ini' );
		$ini      = ( new \Pixel418\Iniliq )->parse( $files );
		$this->assertEquals( $ini, [ 'extensions' => [ 'Extension1', 'Extension2', 'Extension3' ] ] );
	}



	// FILE ADDING LIST VALUES
	public function test_parsing_a_file_with_removing_list( ) {
		$file = realpath( __DIR__ . '/../resource/list-remove.ini' );
		$ini  = ( new \Pixel418\Iniliq )->parse( $file );
		$this->assertEquals( $ini, [ 'extensions' => [ ] ] );
	}

	public function test_parsing_a_file_with_removing_list_to_himself( ) {
		$file  = realpath( __DIR__ . '/../resource/list-remove.ini' );
		$files = [ $file, $file ];
		$ini   = ( new \Pixel418\Iniliq )->parse( $files );
		$this->assertEquals( $ini, [ 'extensions' => [ ] ] );
	}

	public function test_parsing_a_file_with_removing_list_to_another_one( ) {
		$files    = [ ];
		$files[ ] = realpath( __DIR__ . '/../resource/list.ini' );
		$files[ ] = realpath( __DIR__ . '/../resource/list-remove.ini' );
		$ini      = ( new \Pixel418\Iniliq )->parse( $files );
		$this->assertEquals( $ini, [ 'extensions' => [ 'Extension2' ] ] );
	}
}