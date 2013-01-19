<?php

/* This file is part of the Iniliq project, which is under MIT license */

namespace Pixel418\Iniliq;


class ArrayObject extends \ArrayObject {



	/*************************************************************************
	  CONVERT METHODS				   
	 *************************************************************************/
	public function to_array( ) {
		return $this->getArrayCopy( );
	}



	/*************************************************************************
	  SIMPLE ACCESSOR METHODS				   
	 *************************************************************************/
	public function has( $index ) {
		return $this->offsetExists( $index );
	}

	public function get( $index , $default = NULL ) {
		if ( $this->has( $index ) ) {
			return $this->offsetGet( $index );
		}
		return $default;
	}

	public function get_as_boolean( $index, $default = NULL ) {
		$value = $this->get( $index, $default );
		return ( ! empty( $value ) && $value !== 'off' );
	}

	public function get_as_constant( $index, $default = NULL ) {
		$value = $this->get( $index );
		if ( defined( $value ) ) {
			return constant( $value );
		}
		return $default;
	}

	public function get_as_array( $index, $default = [ ] ) {
		$value = $this->get( $index, $default );
		return \UArray::convert_to_array( $value );
	}



	/*************************************************************************
	  HERITED ACCESSOR METHODS				   
	 *************************************************************************/
	public function offsetExists( $index ) {
		return \Pixel418\Iniliq::has_deep_selector( $this->getArrayCopy( ), $index );
	}
 
	public function offsetGet( $index ) {
		return \Pixel418\Iniliq::get_deep_selector( $this->getArrayCopy( ), $index );
	}
 
	public function offsetSet( $index, $newval ) {
		$new_array = \Pixel418\Iniliq::set_deep_selector( $this->getArrayCopy( ), $index, $newval );
		$this->exchangeArray( $new_array );
	}
 
	public function offsetUnset( $index ) {
		$new_array = \Pixel418\Iniliq::unset_deep_selector( $this->getArrayCopy( ), $index );
		$this->exchangeArray( $new_array );
	}
}