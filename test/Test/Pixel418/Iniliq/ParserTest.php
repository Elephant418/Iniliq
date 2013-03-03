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
		$this->assertInstanceOf( 'Pixel418\Iniliq\ArrayObject', $ini );
		$this->assertEquals( array( ), $ini->toArray( ) );
	}

	public function test_parsing_bad_format__exception( ) {
		$ini  = new Parser( \Pixel418\Iniliq::ERROR_AS_EXCEPTION );
		$this->setExpectedException( 'Pixel418\Iniliq\FileNotFoundException' );
		$ini  = $ini->parse( 'dropdowntrululu' );
	}

	public function test_parsing_bad_format__error( ) {
		$ini  = new Parser( \Pixel418\Iniliq::ERROR_AS_PHPERROR );
		// $this->setExpectedException( 'PHPUnit_Framework_Error' );
		$ini  = $ini->parse( array( ), FALSE );
	}

	public function test_parsing_unexisting_file__quiet( ) {
		$ini  = new Parser;
		$ini  = $ini->parse( 'dropdowntrululu' );
		$this->assertEquals( array( ), $ini->toArray( ) );
	}

	public function test_parsing_unexisting_file__exception( ) {
		$ini  = new Parser( \Pixel418\Iniliq::ERROR_AS_EXCEPTION );
		$this->setExpectedException( 'Pixel418\Iniliq\FileNotFoundException' );
		$ini  = $ini->parse( 'dropdowntrululu' );
	}

	public function test_parsing_unexisting_file__error( ) {
		$ini  = new Parser( \Pixel418\Iniliq::ERROR_AS_PHPERROR );
		$this->setExpectedException( 'PHPUnit_Framework_Error' );
		$ini  = $ini->parse( 'dropdowntrululu' );
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

	public function test_parsing_empty__result_as_array( ) {
		$ini  = new Parser( \Pixel418\Iniliq::RESULT_AS_ARRAY);
		$ini  = $ini->parse( '' );
		$this->assertInternalType( 'array', $ini );
		$this->assertEquals( array( ), $ini );
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

	public function test_parsing_a_file_with_adding_list__disabled( ) {
		$file = $this->resource_dir . '/list-add.ini';
		$ini  = new Parser( \Pixel418\Iniliq::DISABLE_APPEND_SELECTORS );
		$ini = $ini->parse( $file );
		$this->assertEquals( array( 'extensions +' => 'Extension3' ), $ini->toArray( ) );
	}

	public function test_parsing_a_file_with_adding_list__disabled__second_arg( ) {
		$file = $this->resource_dir . '/list-add.ini';
		$ini  = new Parser( \Pixel418\Iniliq::ENABLE_JSON_VALUES, \Pixel418\Iniliq::DISABLE_APPEND_SELECTORS );
		$ini = $ini->parse( $file );
		$this->assertEquals( array( 'extensions +' => 'Extension3' ), $ini->toArray( ) );
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

	public function test_parsing_a_file_with_removing_list__disabled( ) {
		$file = $this->resource_dir . '/list-remove.ini';
		$ini  = new Parser( \Pixel418\Iniliq::DISABLE_APPEND_SELECTORS );
		$ini = $ini->parse( $file );
		$this->assertEquals( array( 'extensions -' => 'Extension1' ), $ini->toArray( ) );
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

	public function test_parsing_deep_selectors__disable( ) {
		$ini  = new Parser( \Pixel418\Iniliq::DISABLE_DEEP_SELECTORS );
		$deepSelector = array( 'person.creator.name' => 'Thomas', 'person.creator.role' => array( 'Developer' ) );
		$ini = $ini->parse( array( ), $deepSelector );
		$this->assertEquals( $deepSelector, $ini->toArray( ) );
	}



	/*************************************************************************
	  JSON VALUE INI TESTS				   
	 *************************************************************************/
	public function test_parsing_json_values__valid( ) {
		$ini  = new Parser;
		$ini = $ini->parse( array( ), array( 'person.creator' => '{ "name": "Thomas", "role": [ "Developer" ] }' ) );
		$assert = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$this->assertEquals( $assert, $ini->toArray( ) );
	}

	public function test_parsing_json_values__without_quote( ) {
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

	public function test_parsing_json_values__disabled( ) {
		$ini = new Parser( \Pixel418\Iniliq::DISABLE_JSON_VALUES );
		$jsonValues = array( 'person' => '{ name: Thomas, role: [ Developer ] }' );
		$ini = $ini->parse( array( ), $jsonValues );
		$this->assertEquals( $jsonValues, $ini->toArray( ) );
	}
}