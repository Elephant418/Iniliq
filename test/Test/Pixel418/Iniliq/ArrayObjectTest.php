<?php

namespace Test\Pixel418\Iniliq;

use \Pixel418\Iniliq\ArrayObject as ArrayObject;

require_once( __DIR__ . '/../../../../vendor/autoload.php' );

class ArrayObjectTest extends \PHPUnit_Framework_TestCase {



	/*************************************************************************
	  DEEP SELECTOR TESTS
	 *************************************************************************/
	public function test_deep_selector__isset__match( ) {
		$array = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$result = new ArrayObject( $array );
		$this->assertTrue( isset( $result[ 'person.creator.name' ] ) );
	}

	public function test_deep_selector__isset__no_match( ) {
		$array = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$result = new ArrayObject( $array );
		$this->assertFalse( isset( $result[ 'person.creator.organization' ] ) );
	}

	public function test_deep_selector__isset__disabled( ) {
		$array = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$result = new ArrayObject( $array, \Pixel418\Iniliq::DISABLE_DEEP_SELECTORS );
		$this->assertFalse( isset( $result[ 'person.creator.name' ] ) );
	}

	public function test_deep_selector__get__match( ) {
		$array = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$result = new ArrayObject( $array );
		$this->assertEquals( 'Thomas', $result[ 'person.creator.name' ] );
	}

	public function test_deep_selector__get__no_match( ) {
		$array = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$result = new ArrayObject( $array );
		$this->assertNull( $result[ 'person.creator.organization' ] );
	}

	public function test_deep_selector__get__no_match__exception( ) {
		$array = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$result = new ArrayObject( $array, \Pixel418\Iniliq::ERROR_AS_EXCEPTION );
		$this->setExpectedException( 'Exception' );
		$test = $result[ 'person.creator.organization' ];
	}

	public function test_deep_selector__get__disabled( ) {
		$array = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$result = new ArrayObject( $array, \Pixel418\Iniliq::DISABLE_DEEP_SELECTORS );
		$this->assertNull( $result[ 'person.creator.name' ] );
	}

	public function test_deep_selector__set__simple( ) {
		$array = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$result = new ArrayObject( $array );
		$result[ 'person.creator.organization' ] = 'Pixel418';
		$new_array = $result->toArray( );
		$this->assertTrue( isset( $new_array[ 'person' ][ 'creator'][ 'organization' ] ) );
		$this->assertEquals( 'Pixel418', $new_array[ 'person' ][ 'creator'][ 'organization' ] );
	}

	public function test_deep_selector__set__deep( ) {
		$result = new ArrayObject( array( ) );
		$result[ 'person.creator.organization' ] = 'Pixel418';
		$new_array = $result->toArray( );
		$this->assertTrue( isset( $new_array[ 'person' ][ 'creator'][ 'organization' ] ) );
		$this->assertEquals( 'Pixel418', $new_array[ 'person' ][ 'creator'][ 'organization' ] );
	}

	public function test_deep_selector__set__disabled( ) {
		$result = new ArrayObject( array( ), \Pixel418\Iniliq::DISABLE_DEEP_SELECTORS );
		$result[ 'person.creator.organization' ] = 'Pixel418';
		$new_array = $result->toArray( );
		$this->assertFalse( isset( $new_array[ 'person' ][ 'creator'][ 'organization' ] ) );
		$this->assertTrue( isset( $new_array[ 'person.creator.organization' ] ) );
		$this->assertEquals( 'Pixel418', $new_array[ 'person.creator.organization' ] );
	}

	public function test_deep_selector__unset__no_match( ) {
		$array = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$result = new ArrayObject( $array );
		unset( $result[ 'person.creator.id' ] );
	}

	public function test_deep_selector__unset__no_match__exception( ) {
		$array = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$result = new ArrayObject( $array, \Pixel418\Iniliq::ERROR_AS_EXCEPTION );
		$this->setExpectedException( 'Exception' );
		unset( $result[ 'person.creator.id' ] );
	}

	public function test_deep_selector__unset__match( ) {
		$array = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$result = new ArrayObject( $array );
		unset( $result[ 'person.creator.name' ] );
		$new_array = $result->toArray( );
		$this->assertFalse( isset( $new_array[ 'person' ][ 'creator'][ 'name' ] ) );
	}

	public function test_deep_selector__unset__disabled( ) {
		$array = array( 'person' => array( 'creator' => array( 'name' => 'Thomas', 'role' => array( 'Developer' ) ) ) );
		$result = new ArrayObject( $array, \Pixel418\Iniliq::DISABLE_DEEP_SELECTORS );
		unset( $result[ 'person.creator.name' ] );
		$new_array = $result->toArray( );
		$this->assertTrue( isset( $new_array[ 'person' ][ 'creator'][ 'name' ] ) );
	}



	/*************************************************************************
	  GETTER FORMATED TESTS
	 *************************************************************************/
	public function test_getter_formated__boolean__true( ) {
		$array = array( 'boolean' => TRUE );
		$result = new ArrayObject( $array );
		$this->assertTrue( $result->getAsBoolean( 'boolean' ) );
	}

	public function test_getter_formated__boolean__false( ) {
		$array = array( 'boolean' => FALSE );
		$result = new ArrayObject( $array );
		$this->assertFalse( $result->getAsBoolean( 'boolean' ) );
	}

	public function test_getter_formated__boolean__null( ) {
		$array = array( 'boolean' => NULL );
		$result = new ArrayObject( $array );
		$this->assertFalse( $result->getAsBoolean( 'boolean' ) );
	}

	public function test_getter_formated__boolean__on( ) {
		$array = array( 'boolean' => 'on' );
		$result = new ArrayObject( $array );
		$this->assertTrue( $result->getAsBoolean( 'boolean' ) );
	}

	public function test_getter_formated__boolean__off( ) {
		$array = array( 'boolean' => 'off' );
		$result = new ArrayObject( $array );
		$this->assertFalse( $result->getAsBoolean( 'boolean' ) );
	}

	public function test_getter_formated__boolean__1( ) {
		$array = array( 'boolean' => 1 );
		$result = new ArrayObject( $array );
		$this->assertTrue( $result->getAsBoolean( 'boolean' ) );
	}

	public function test_getter_formated__boolean__0( ) {
		$array = array( 'boolean' => 0 );
		$result = new ArrayObject( $array );
		$this->assertFalse( $result->getAsBoolean( 'boolean' ) );
	}

	public function test_getter_formated__constant__defined( ) {
		$array = array( 'constant' => 'PHP_EOL' );
		$result = new ArrayObject( $array );
		$this->assertEquals( PHP_EOL, $result->getAsConstant( 'constant' ) );
	}

	public function test_getter_formated__constant__not_defined( ) {
		$array = array( 'constant' => 'COCO' );
		$result = new ArrayObject( $array );
		$this->assertNull( $result->getAsConstant( 'constant' ) );
	}

	public function test_getter_formated__constant__not_defined_with_default( ) {
		$array = array( 'constant' => 'COCO' );
		$result = new ArrayObject( $array );
		$this->assertEquals( 0, $result->getAsConstant( 'constant', 0 ) );
	}

	public function test_getter_formated__array__match( ) {
		$array = array( 'array' => array( 'string' ) );
		$result = new ArrayObject( $array );
		$this->assertEquals( array( 'string' ), $result->getAsArray( 'array' ) );
	}

	public function test_getter_formated__array__match_string( ) {
		$array = array( 'array' => 'string' );
		$result = new ArrayObject( $array );
		$this->assertEquals( array( 'string' ), $result->getAsArray( 'array' ) );
	}

	public function test_getter_formated__array__not_defined( ) {
		$array = array( 'constant' => 'COCO' );
		$result = new ArrayObject( $array );
		$this->assertEquals( array( ), $result->getAsArray( 'array' ) );
	}
}