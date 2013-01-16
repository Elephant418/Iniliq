<?php

namespace Test\Pixel418\Iniliq;

use \Pixel418\Iniliq\Result as Result;

require_once( __DIR__ . '/../../../vendor/autoload.php' );

class Result_Test extends \PHPUnit_Framework_TestCase {



	// DEEP SELECTOR
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
		$this->assertEquals( $result[ 'person.creator.name' ], 'Thomas' );
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
		$this->assertEquals( $new_array[ 'person' ][ 'creator'][ 'organization' ], 'Pixel418' );
	}

	public function test_deep_selector__set__deep( ) {
		$result = new Result( [ ] );
		$result[ 'person.creator.organization' ] = 'Pixel418';
		$new_array = $result->to_array( );
		$this->assertTrue( isset( $new_array[ 'person' ][ 'creator'][ 'organization' ] ) );
		$this->assertEquals( $new_array[ 'person' ][ 'creator'][ 'organization' ], 'Pixel418' );
	}

	public function test_deep_selector__unset__match( ) {
		$array = [ 'person' => [ 'creator' => [ 'name' => 'Thomas', 'role' => [ 'Developer' ] ] ] ];
		$result = new Result( $array );
		unset( $result[ 'person.creator.name' ] );
		$new_array = $result->to_array( );
		$this->assertFalse( isset( $new_array[ 'person' ][ 'creator'][ 'name' ] ) );
	}
}