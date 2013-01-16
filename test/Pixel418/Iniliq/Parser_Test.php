<?php

namespace Test\Pixel418\Iniliq;

use \Pixel418\Iniliq\Parser as Parser;

require_once( __DIR__ . '/../../../vendor/autoload.php' );

echo 'Iniliq ' . \Pixel418\Iniliq::VERSION . ' tested with ';

class Parser_Test extends \PHPUnit_Framework_TestCase {



    /*************************************************************************
      SIMPLE INI TESTS                   
     *************************************************************************/
	public function test_parsing_a_simple_file( ) {
		$file = realpath( __DIR__ . '/../../resource/simple.ini' );
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( $ini->to_array( ), parse_ini_file( $file, TRUE ) );
	}

	public function test_parsing_a_simple_file_two_times( ) {
		$file  = realpath( __DIR__ . '/../../resource/simple.ini' );
		$files = [ $file, $file ];
		$ini   = ( new Parser )->parse( $files );
		$this->assertEquals( $ini->to_array( ), parse_ini_file( $file, TRUE ) );
	}

	public function test_parsing_a_simple_file_with_init_values( ) {
		$file    = realpath( __DIR__ . '/../../resource/simple.ini' );
		$files   = [ $file, $file ];
		$default = [ 'default_value' => 'on' ];
		$ini     = ( new Parser )->parse( $files, $default );
		$assert  = array_merge( parse_ini_file( $file, TRUE ), $default ); 
		$this->assertEquals( $ini->to_array( ), $assert );
	}



    /*************************************************************************
      LIST INI TESTS                   
     *************************************************************************/
	public function test_parsing_a_file_with_list( ) {
		$file = realpath( __DIR__ . '/../../resource/list.ini' );
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( $ini->to_array( ), parse_ini_file( $file, TRUE ) );
	}

	public function test_parsing_a_file_with_list_two_times( ) {
		$file  = realpath( __DIR__ . '/../../resource/list.ini' );
		$files = [ $file, $file ];
		$ini   = ( new Parser )->parse( $files );
		$this->assertEquals( $ini->to_array( ), parse_ini_file( $file, TRUE ) );
	}



    /*************************************************************************
      ADDING LIST INI TESTS                   
     *************************************************************************/
	public function test_parsing_a_file_with_adding_list( ) {
		$file = realpath( __DIR__ . '/../../resource/list-add.ini' );
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( $ini->to_array( ), [ 'extensions' => [ 'Extension3' ] ] );
	}

	public function test_parsing_a_file_with_adding_list_to_himself( ) {
		$file  = realpath( __DIR__ . '/../../resource/list-add.ini' );
		$files = [ $file, $file ];
		$ini   = ( new Parser )->parse( $files );
		$this->assertEquals( $ini->to_array( ), [ 'extensions' => [ 'Extension3', 'Extension3' ] ] );
	}

	public function test_parsing_a_file_with_adding_list_to_another_one( ) {
		$files    = [ ];
		$files[ ] = realpath( __DIR__ . '/../../resource/list.ini' );
		$files[ ] = realpath( __DIR__ . '/../../resource/list-add.ini' );
		$ini      = ( new Parser )->parse( $files );
		$this->assertEquals( $ini->to_array( ), [ 'extensions' => [ 'Extension1', 'Extension2', 'Extension3' ] ] );
	}



    /*************************************************************************
      REMOVING LIST INI TESTS                   
     *************************************************************************/
	public function test_parsing_a_file_with_removing_list( ) {
		$file = realpath( __DIR__ . '/../../resource/list-remove.ini' );
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( $ini->to_array( ), [ 'extensions' => [ ] ] );
	}

	public function test_parsing_a_file_with_removing_list_to_himself( ) {
		$file  = realpath( __DIR__ . '/../../resource/list-remove.ini' );
		$files = [ $file, $file ];
		$ini   = ( new Parser )->parse( $files );
		$this->assertEquals( $ini->to_array( ), [ 'extensions' => [ ] ] );
	}

	public function test_parsing_a_file_with_removing_list_to_another_one( ) {
		$files    = [ ];
		$files[ ] = realpath( __DIR__ . '/../../resource/list.ini' );
		$files[ ] = realpath( __DIR__ . '/../../resource/list-remove.ini' );
		$ini      = ( new Parser )->parse( $files );
		$this->assertEquals( $ini->to_array( ), [ 'extensions' => [ 'Extension2' ] ] );
	}



    /*************************************************************************
      DEEP SELECTOR INI TESTS                   
     *************************************************************************/
	public function test_parsing_deep_selectors( ) {
		$ini  = ( new Parser )->parse( [ ], [ 'person.creator.name' => 'Thomas', 'person.creator.role' => [ 'Developer' ] ] );
		$this->assertEquals( $ini->to_array( ), [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ] );
	}

	public function test_parsing_a_file_with_deep_selectors( ) {
		$file = realpath( __DIR__ . '/../../resource/deep-selector.ini' );
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( $ini->to_array( ), [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ] );
	}



    /*************************************************************************
      JSON VALUE INI TESTS                   
     *************************************************************************/
	public function test_parsing_json_values( ) {
		$ini  = ( new Parser )->parse( [ ], [ 'person.creator' => '{ name: Thomas, role: [ Developer ] }' ] );
		$this->assertEquals( $ini->to_array( ), [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ] );
	}

	public function test_parsing_a_file_with_json_values( ) {
		$file = realpath( __DIR__ . '/../../resource/json-value.ini' );
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( $ini->to_array( ), [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ] );
	}
}