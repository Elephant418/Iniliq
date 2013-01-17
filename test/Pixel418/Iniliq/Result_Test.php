<?php

namespace Test\Pixel418\Iniliq;

use \Pixel418\Iniliq\Result as Result;

require_once( __DIR__ . '/../../../vendor/autoload.php' );

class Result_Test extends \PHPUnit_Framework_TestCase {



    /*************************************************************************
      DEEP SELECTOR TESTS
     *************************************************************************/
	public function test_deep_selector__set__match( ) {
		$array = [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ];
		$result = new Result( $array );
		$this->assertTrue( isset( $result[ 'person.creator.name' ] ) );
	}

	public function test_deep_selector__set__no_match( ) {
		$array = [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ];
		$result = new Result( $array );
		$this->assertFalse( isset( $result[ 'person.creator.organization' ] ) );
	}

	public function test_deep_selector__get__match( ) {
		$array = [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ];
		$result = new Result( $array );
		$this->assertEquals( 'Thomas', $result[ 'person.creator.name' ] );
	}

	public function test_deep_selector__get__no_match( ) {
		$array = [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ];
		$result = new Result( $array );
		$this->assertNull( $result[ 'person.creator.organization' ] );
	}

	public function test_deep_selector__set__simple( ) {
		$array = [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ];
		$result = new Result( $array );
		$result[ 'person.creator.organization' ] = 'Pixel418';
		$new_array = $result->to_array( );
		$this->assertTrue( isset( $new_array[ 'person' ][ 'creator'][ 'organization' ] ) );
		$this->assertEquals( 'Pixel418', $new_array[ 'person' ][ 'creator'][ 'organization' ] );
	}

	public function test_deep_selector__set__deep( ) {
		$result = new Result( [ ] );
		$result[ 'person.creator.organization' ] = 'Pixel418';
		$new_array = $result->to_array( );
		$this->assertTrue( isset( $new_array[ 'person' ][ 'creator'][ 'organization' ] ) );
		$this->assertEquals( 'Pixel418', $new_array[ 'person' ][ 'creator'][ 'organization' ] );
	}

	public function test_deep_selector__unset__match( ) {
		$array = [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ];
		$result = new Result( $array );
		unset( $result[ 'person.creator.name' ] );
		$new_array = $result->to_array( );
		$this->assertFalse( isset( $new_array[ 'person' ][ 'creator'][ 'name' ] ) );
	}



    /*************************************************************************
      GETTER FORMATED TESTS
     *************************************************************************/
	public function test_getter_formated__boolean__true( ) {
		$array = [ 'boolean' => TRUE ];
		$result = new Result( $array );
		$this->assertTrue( $result->get_as_boolean( 'boolean' ) );
	}

	public function test_getter_formated__boolean__false( ) {
		$array = [ 'boolean' => FALSE ];
		$result = new Result( $array );
		$this->assertFalse( $result->get_as_boolean( 'boolean' ) );
	}

	public function test_getter_formated__boolean__null( ) {
		$array = [ 'boolean' => NULL ];
		$result = new Result( $array );
		$this->assertFalse( $result->get_as_boolean( 'boolean' ) );
	}

	public function test_getter_formated__boolean__on( ) {
		$array = [ 'boolean' => 'on' ];
		$result = new Result( $array );
		$this->assertTrue( $result->get_as_boolean( 'boolean' ) );
	}

	public function test_getter_formated__boolean__off( ) {
		$array = [ 'boolean' => 'off' ];
		$result = new Result( $array );
		$this->assertFalse( $result->get_as_boolean( 'boolean' ) );
	}

	public function test_getter_formated__boolean__1( ) {
		$array = [ 'boolean' => 1 ];
		$result = new Result( $array );
		$this->assertTrue( $result->get_as_boolean( 'boolean' ) );
	}

	public function test_getter_formated__boolean__0( ) {
		$array = [ 'boolean' => 0 ];
		$result = new Result( $array );
		$this->assertFalse( $result->get_as_boolean( 'boolean' ) );
	}

	public function test_getter_formated__constant__defined( ) {
		$array = [ 'constant' => 'PHP_EOL' ];
		$result = new Result( $array );
		$this->assertEquals( PHP_EOL, $result->get_as_constant( 'constant' ) );
	}

	public function test_getter_formated__constant__not_defined( ) {
		$array = [ 'constant' => 'COCO' ];
		$result = new Result( $array );
		$this->assertNull( $result->get_as_constant( 'constant' ) );
	}

	public function test_getter_formated__constant__not_defined_with_default( ) {
		$array = [ 'constant' => 'COCO' ];
		$result = new Result( $array );
		$this->assertEquals( 0, $result->get_as_constant( 'constant', '0' ) );
	}
}