<?php

namespace Test\Iniliq;

use \Pixel418\Iniliq\Parser as Parser;

require_once( __DIR__ . '/../../vendor/autoload.php' );

echo 'Iniliq ' . \Pixel418\Iniliq::VERSION . ' tested with ';

class Iniliq_Test extends \PHPUnit_Framework_TestCase {



	// FILE WITH SIMPLE KEY -> VALUE DATA
	public function test_parsing_a_simple_file( ) {
		$file = realpath( __DIR__ . '/../resource/simple.ini' );
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( $ini, parse_ini_file( $file, TRUE ) );
	}

	public function test_parsing_a_simple_file_two_times( ) {
		$file  = realpath( __DIR__ . '/../resource/simple.ini' );
		$files = [ $file, $file ];
		$ini   = ( new Parser )->parse( $files );
		$this->assertEquals( $ini, parse_ini_file( $file, TRUE ) );
	}

	public function test_parsing_a_simple_file_with_init_values( ) {
		$file    = realpath( __DIR__ . '/../resource/simple.ini' );
		$files   = [ $file, $file ];
		$default = [ 'default_value' => 'on' ];
		$ini     = ( new Parser )->parse( $files, $default );
		$assert  = array_merge( parse_ini_file( $file, TRUE ), $default ); 
		$this->assertEquals( $ini, $assert );
	}



	// FILE WITH KEY -> LIST VALUES
	public function test_parsing_a_file_with_list( ) {
		$file = realpath( __DIR__ . '/../resource/list.ini' );
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( $ini, parse_ini_file( $file, TRUE ) );
	}

	public function test_parsing_a_file_with_list_two_times( ) {
		$file  = realpath( __DIR__ . '/../resource/list.ini' );
		$files = [ $file, $file ];
		$ini   = ( new Parser )->parse( $files );
		$this->assertEquals( $ini, parse_ini_file( $file, TRUE ) );
	}



	// FILE ADDING LIST VALUES
	public function test_parsing_a_file_with_adding_list( ) {
		$file = realpath( __DIR__ . '/../resource/list-add.ini' );
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( $ini, [ 'extensions' => [ 'Extension3' ] ] );
	}

	public function test_parsing_a_file_with_adding_list_to_himself( ) {
		$file  = realpath( __DIR__ . '/../resource/list-add.ini' );
		$files = [ $file, $file ];
		$ini   = ( new Parser )->parse( $files );
		$this->assertEquals( $ini, [ 'extensions' => [ 'Extension3', 'Extension3' ] ] );
	}

	public function test_parsing_a_file_with_adding_list_to_another_one( ) {
		$files    = [ ];
		$files[ ] = realpath( __DIR__ . '/../resource/list.ini' );
		$files[ ] = realpath( __DIR__ . '/../resource/list-add.ini' );
		$ini      = ( new Parser )->parse( $files );
		$this->assertEquals( $ini, [ 'extensions' => [ 'Extension1', 'Extension2', 'Extension3' ] ] );
	}



	// FILE ADDING LIST VALUES
	public function test_parsing_a_file_with_removing_list( ) {
		$file = realpath( __DIR__ . '/../resource/list-remove.ini' );
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( $ini, [ 'extensions' => [ ] ] );
	}

	public function test_parsing_a_file_with_removing_list_to_himself( ) {
		$file  = realpath( __DIR__ . '/../resource/list-remove.ini' );
		$files = [ $file, $file ];
		$ini   = ( new Parser )->parse( $files );
		$this->assertEquals( $ini, [ 'extensions' => [ ] ] );
	}

	public function test_parsing_a_file_with_removing_list_to_another_one( ) {
		$files    = [ ];
		$files[ ] = realpath( __DIR__ . '/../resource/list.ini' );
		$files[ ] = realpath( __DIR__ . '/../resource/list-remove.ini' );
		$ini      = ( new Parser )->parse( $files );
		$this->assertEquals( $ini, [ 'extensions' => [ 'Extension2' ] ] );
	}



	// FILE WITH DEEP SELECTORS
	public function test_parsing_deep_selectors( ) {
		$ini  = ( new Parser )->parse( [ ], [ 'person.creator.name' => 'Thomas', 'person.creator.role' => [ 'Developer' ] ] );
		$this->assertEquals( $ini, [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ] );
	}

	public function test_parsing_a_file_with_deep_selectors( ) {
		$file = realpath( __DIR__ . '/../resource/deep-selector.ini' );
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( $ini, [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ] );
	}



	// FILE WITH JSON VALUES
	public function test_parsing_json_values( ) {
		$ini  = ( new Parser )->parse( [ ], [ 'person.creator' => '{ name: Thomas, role: [ Developer ] }' ] );
		$this->assertEquals( $ini, [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ] );
	}

	public function test_parsing_a_file_with_json_values( ) {
		$file = realpath( __DIR__ . '/../resource/json-value.ini' );
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( $ini, [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ] );
	}
}