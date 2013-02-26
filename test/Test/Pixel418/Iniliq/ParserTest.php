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
		$ini  = new Parser;
		$ini  = $ini->parse( '' );
		$this->assertEquals( array( ), $ini->toArray( ) );
	}

	public function test_parsing_a_simple_file( ) {
		$file = $this->resource_dir . '/simple.ini';
		$ini  = new Parser;
		$ini = $ini->parse( $file );
		$this->assertEquals( parse_ini_file( $file, TRUE ), $ini->toArray( ) );
	}

	public function test_parsing_a_simple_file_two_times( ) {
		$file  = $this->resource_dir . '/simple.ini';
		$files = array( $file, $file );
		$ini   = new Parser;
		$ini = $ini->parse( $files );
		$this->assertEquals( parse_ini_file( $file, TRUE ), $ini->toArray( ) );
	}

	public function test_parsing_a_simple_file_with_init_values( ) {
		$file	 = $this->resource_dir . '/simple.ini';
		$files   = array( $file, $file );
		$default = array( 'default_value' => 'on' );
		$ini	 = new Parser;
		$ini     = $ini->parse( $files, $default );
		$assert  = array_merge( parse_ini_file( $file, TRUE ), $default ); 
		$this->assertEquals( $assert, $ini->toArray( ) );
	}



	/*************************************************************************
	  LIST INI TESTS				   
	 *************************************************************************/
	public function test_parsing_a_file_with_list( ) {
		$file = $this->resource_dir . '/list.ini';
		$ini  = new Parser;
		$ini = $ini->parse( $file );
		$this->assertEquals( parse_ini_file( $file, TRUE ), $ini->toArray( ) );
	}

	public function test_parsing_a_file_with_list_two_times( ) {
		$file  = $this->resource_dir . '/list.ini';
		$files = array( $file, $file );
		$ini   = new Parser;
		$ini = $ini->parse( $files );
		$this->assertEquals( parse_ini_file( $file, TRUE ), $ini->toArray( ) );
	}



	/*************************************************************************
	  ADDING LIST INI TESTS				   
	 *************************************************************************/
	public function test_parsing_a_file_with_adding_list( ) {
		$file = $this->resource_dir . '/list-add.ini';
		$ini  = new Parser;
		$ini = $ini->parse( $file );
		$this->assertEquals( array( 'extensions' => array( 'Extension3' ) ), $ini->toArray( ) );
	}

	public function test_parsing_a_file_with_adding_list_to_himself( ) {
		$file  = $this->resource_dir . '/list-add.ini';
		$files = array( $file, $file );
		$ini   = new Parser;
		$ini = $ini->parse( $files );
		$this->assertEquals( array( 'extensions' => array( 'Extension3', 'Extension3' ) ), $ini->toArray( ) );
	}

	public function test_parsing_a_file_with_adding_list_to_another_one( ) {
		$files	= array( );
		$files[ ] = $this->resource_dir . '/list.ini';
		$files[ ] = $this->resource_dir . '/list-add.ini';
		$ini      = new Parser;
		$ini = $ini->parse( $files );
		$this->assertEquals( array( 'extensions' => array( 'Extension1', 'Extension2', 'Extension3' ) ), $ini->toArray( ) );
	}



	/*************************************************************************
	  REMOVING LIST INI TESTS				   
	 *************************************************************************/
	public function test_parsing_a_file_with_removing_list( ) {
		$file = $this->resource_dir . '/list-remove.ini';
		$ini  = new Parser;
		$ini = $ini->parse( $file );
		$this->assertEquals( array( 'extensions' => array( ) ), $ini->toArray( ) );
	}

	public function test_parsing_a_file_with_removing_list_to_himself( ) {
		$file  = $this->resource_dir . '/list-remove.ini';
		$files = array( $file, $file );
		$ini   = new Parser;
		$ini = $ini->parse( $files );
		$this->assertEquals( array( 'extensions' => array( ) ), $ini->toArray( ) );
	}

	public function test_parsing_a_file_with_removing_list_to_another_one( ) {
		$files	= array( );
		$files[ ] = $this->resource_dir . '/list.ini';
		$files[ ] = $this->resource_dir . '/list-remove.ini';
		$ini	  = new Parser;
		$ini      = $ini->parse( $files );
		$this->assertEquals( array( 'extensions' => array( 'Extension2' ) ), $ini->toArray( ) );
	}



	/*************************************************************************
	  DEEP SELECTOR INI TESTS				   
	 *************************************************************************/
	public function test_parsing_deep_selectors( ) {
		$ini  = new Parser;
		$ini = $ini->parse( array( ), array( 'person.creator.name' => 'Thomas', 'person.creator.role' => array( 'Developer' ) ) );
		$assert = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$this->assertEquals( $assert, $ini->toArray( ) );
	}

	public function test_parsing_a_file_with_deep_selectors( ) {
		$file = $this->resource_dir . '/deep-selector.ini';
		$ini  = new Parser;
		$ini = $ini->parse( $file );
		$assert = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$this->assertEquals( $assert, $ini->toArray( ) );
	}



	/*************************************************************************
	  JSON VALUE INI TESTS				   
	 *************************************************************************/
	public function test_parsing_json_values( ) {
		$ini  = new Parser;
		$ini = $ini->parse( array( ), array( 'person.creator' => '{ name: Thomas, role: [ Developer ] }' ) );
		$assert = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$this->assertEquals( $assert, $ini->toArray( ) );
	}

	public function test_parsing_a_file_with_json_values( ) {
		$file = $this->resource_dir . '/json-value.ini';
		$ini  = new Parser;
		$ini = $ini->parse( $file );
		$assert = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$this->assertEquals( $assert, $ini->toArray( ) );
	}
}