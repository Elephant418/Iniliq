<?php

namespace Test\Pixel418\Iniliq;

use \Pixel418\Iniliq\Parser as Parser;

require_once( __DIR__ . '/../../../../vendor/autoload.php' );

echo 'Iniliq ' . \Pixel418\Iniliq::VERSION . ' tested with ';

class ParserTest extends \PHPUnit_Framework_TestCase {



	/*************************************************************************
	 FIXTURE METHODS
	 *************************************************************************/
	public function setUp( ) {
		$this->resource_dir = realpath( __DIR__ . '/../../../resource' );
	}



	/*************************************************************************
	  SIMPLE INI TESTS				   
	 *************************************************************************/
	public function test_parsing_empty( ) {
		$ini  = ( new Parser )->parse( '' );
		$this->assertEquals( [ ], $ini->to_array( ) );
	}

	public function test_parsing_a_simple_file( ) {
		$file = $this->resource_dir . '/simple.ini';
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( parse_ini_file( $file, TRUE ), $ini->to_array( ) );
	}

	public function test_parsing_a_simple_file_two_times( ) {
		$file  = $this->resource_dir . '/simple.ini';
		$files = [ $file, $file ];
		$ini   = ( new Parser )->parse( $files );
		$this->assertEquals( parse_ini_file( $file, TRUE ), $ini->to_array( ) );
	}

	public function test_parsing_a_simple_file_with_init_values( ) {
		$file	 = $this->resource_dir . '/simple.ini';
		$files   = [ $file, $file ];
		$default = [ 'default_value' => 'on' ];
		$ini	 = ( new Parser )->parse( $files, $default );
		$assert  = array_merge( parse_ini_file( $file, TRUE ), $default ); 
		$this->assertEquals( $assert, $ini->to_array( ) );
	}



	/*************************************************************************
	  LIST INI TESTS				   
	 *************************************************************************/
	public function test_parsing_a_file_with_list( ) {
		$file = $this->resource_dir . '/list.ini';
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( parse_ini_file( $file, TRUE ), $ini->to_array( ) );
	}

	public function test_parsing_a_file_with_list_two_times( ) {
		$file  = $this->resource_dir . '/list.ini';
		$files = [ $file, $file ];
		$ini   = ( new Parser )->parse( $files );
		$this->assertEquals( parse_ini_file( $file, TRUE ), $ini->to_array( ) );
	}



	/*************************************************************************
	  ADDING LIST INI TESTS				   
	 *************************************************************************/
	public function test_parsing_a_file_with_adding_list( ) {
		$file = $this->resource_dir . '/list-add.ini';
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( [ 'extensions' => [ 'Extension3' ] ], $ini->to_array( ) );
	}

	public function test_parsing_a_file_with_adding_list_to_himself( ) {
		$file  = $this->resource_dir . '/list-add.ini';
		$files = [ $file, $file ];
		$ini   = ( new Parser )->parse( $files );
		$this->assertEquals( [ 'extensions' => [ 'Extension3', 'Extension3' ] ], $ini->to_array( ) );
	}

	public function test_parsing_a_file_with_adding_list_to_another_one( ) {
		$files	= [ ];
		$files[ ] = $this->resource_dir . '/list.ini';
		$files[ ] = $this->resource_dir . '/list-add.ini';
		$ini	  = ( new Parser )->parse( $files );
		$this->assertEquals( [ 'extensions' => [ 'Extension1', 'Extension2', 'Extension3' ] ], $ini->to_array( ) );
	}



	/*************************************************************************
	  REMOVING LIST INI TESTS				   
	 *************************************************************************/
	public function test_parsing_a_file_with_removing_list( ) {
		$file = $this->resource_dir . '/list-remove.ini';
		$ini  = ( new Parser )->parse( $file );
		$this->assertEquals( [ 'extensions' => [ ] ], $ini->to_array( ) );
	}

	public function test_parsing_a_file_with_removing_list_to_himself( ) {
		$file  = $this->resource_dir . '/list-remove.ini';
		$files = [ $file, $file ];
		$ini   = ( new Parser )->parse( $files );
		$this->assertEquals( [ 'extensions' => [ ] ], $ini->to_array( ) );
	}

	public function test_parsing_a_file_with_removing_list_to_another_one( ) {
		$files	= [ ];
		$files[ ] = $this->resource_dir . '/list.ini';
		$files[ ] = $this->resource_dir . '/list-remove.ini';
		$ini	  = ( new Parser )->parse( $files );
		$this->assertEquals( [ 'extensions' => [ 'Extension2' ] ], $ini->to_array( ) );
	}



	/*************************************************************************
	  DEEP SELECTOR INI TESTS				   
	 *************************************************************************/
	public function test_parsing_deep_selectors( ) {
		$ini  = ( new Parser )->parse( [ ], [ 'person.creator.name' => 'Thomas', 'person.creator.role' => [ 'Developer' ] ] );
		$assert = [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ];
		$this->assertEquals( $assert, $ini->to_array( ) );
	}

	public function test_parsing_a_file_with_deep_selectors( ) {
		$file = $this->resource_dir . '/deep-selector.ini';
		$ini  = ( new Parser )->parse( $file );
		$assert = [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ];
		$this->assertEquals( $assert, $ini->to_array( ) );
	}



	/*************************************************************************
	  JSON VALUE INI TESTS				   
	 *************************************************************************/
	public function test_parsing_json_values( ) {
		$ini  = ( new Parser )->parse( [ ], [ 'person.creator' => '{ name: Thomas, role: [ Developer ] }' ] );
		$assert = [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ];
		$this->assertEquals( $assert, $ini->to_array( ) );
	}

	public function test_parsing_a_file_with_json_values( ) {
		$file = $this->resource_dir . '/json-value.ini';
		$ini  = ( new Parser )->parse( $file );
		$assert = [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ];
		$this->assertEquals( $assert, $ini->to_array( ) );
	}
}