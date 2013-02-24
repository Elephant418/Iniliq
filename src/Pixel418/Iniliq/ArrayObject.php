<?php

/* This file is part of the Iniliq project, which is under MIT license */

namespace Pixel418\Iniliq;


class ArrayObject extends \ArrayObject {



	/*************************************************************************
	  CONVERT METHODS				   
	 *************************************************************************/
	public function toArray( ) {
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

	public function getAsBoolean( $index, $default = NULL ) {
		$value = $this->get( $index, $default );
		return ( ! empty( $value ) && $value !== 'off' );
	}

	public function getAsConstant( $index, $default = NULL ) {
		$value = $this->get( $index );
		if ( defined( $value ) ) {
			return constant( $value );
		}
		return $default;
	}

	public function getAsArray( $index, $default = [ ] ) {
		$value = $this->get( $index, $default );
		return \UArray::convertToArray( $value );
	}

	public function getAsList( $index, $default = [ ] ) {
		return array_values( $this->getAsArray( $index, $default ) );
	}



	/*************************************************************************
	  HERITED ACCESSOR METHODS				   
	 *************************************************************************/
	public function offsetExists( $index ) {
		return \Pixel418\Iniliq::hasDeepSelector( $this->getArrayCopy( ), $index );
	}

	public function offsetGet( $index ) {
		return \Pixel418\Iniliq::getDeepSelector( $this->getArrayCopy( ), $index );
	}
 
	public function offsetSet( $index, $new_val ) {
		$new_array = \Pixel418\Iniliq::setDeepSelector( $this->getArrayCopy( ), $index, $new_val );
		$this->exchangeArray( $new_array );
	}
 
	public function offsetUnset( $index ) {
		$new_array = \Pixel418\Iniliq::unsetDeepSelector( $this->getArrayCopy( ), $index );
		$this->exchangeArray( $new_array );
	}
}