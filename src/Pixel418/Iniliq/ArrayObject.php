<?php

/* This file is part of the Iniliq project, which is under MIT license */

namespace Pixel418\Iniliq;


class ArrayObject extends \ArrayObject {


	/*************************************************************************
	  ATTRIBUTES				   
	 *************************************************************************/
	protected $deepSelectorOption = TRUE;
	protected $errorStrategy;


	/*************************************************************************
	  CONSTRUCTOR METHODS				   
	 *************************************************************************/
	public function __construct( $array, $options = array( ) ) {
		parent::__construct( $array );
		call_user_func_array( array( $this, 'setOptions' ), array_slice( func_get_args( ), 1 ) );
	}



	/*************************************************************************
	  SIMPLE ACCESSOR METHODS				   
	 *************************************************************************/
	public function has( $index ) {
		return $this->offsetExists( $index );
	}

	public function get( $index, $default = NULL ) {
		if ( $this->has( $index ) ) {
			return $this->offsetGet( $index );
		} else if ( is_null( $default ) ) {
			$this->undefinedIndexAcces( $index );
		}
		return $default;
	}

	public function getAsBoolean( $index, $default = NULL ) {
		$value = $this->get( $index, $default );
		return ( ! empty( $value ) && $value !== 'off' );
	}

	public function getAsArray( $index, $default = array( ) ) {
		$value = $this->get( $index, $default );
		return \UArray::convertToArray( $value );
	}

	public function getAsList( $index, $default = array( ) ) {
		return array_values( $this->getAsArray( $index, $default ) );
	}



	/*************************************************************************
	  HERITED ACCESSOR METHODS				   
	 *************************************************************************/
	public function offsetExists( $index ) {
		if ( $this->deepSelectorOption ) {
			return \Pixel418\Iniliq::hasDeepSelector( $this->getArrayCopy( ), $index );
		}
		return parent::offsetExists( $index );
	}

	public function offsetGet( $index ) {
		if ( ! $this->has( $index ) ) {
			$this->undefinedIndexAcces( $index );
			return NULL;
		}
		if ( $this->deepSelectorOption ) {
			return \Pixel418\Iniliq::getDeepSelector( $this->getArrayCopy( ), $index );
		}
		return parent::offsetGet( $index );
	}
 
	public function offsetSet( $index, $new_val ) {
		if ( $this->deepSelectorOption ) {
			$new_array = \Pixel418\Iniliq::setDeepSelector( $this->getArrayCopy( ), $index, $new_val );
			$this->exchangeArray( $new_array );
		}
		return parent::offsetSet( $index, $new_val );
	}
 
	public function offsetUnset( $index ) {
		if ( ! $this->has( $index ) ) {
			$this->undefinedIndexAcces( $index );
			return NULL;
		}
		if ( $this->deepSelectorOption ) {
			$new_array = \Pixel418\Iniliq::unsetDeepSelector( $this->getArrayCopy( ), $index );
			$this->exchangeArray( $new_array );
		} else {
			parent::offsetUnset( $index );
		}
	}



	/*************************************************************************
	  MISCELLANEOUS PUBLIC METHODS				   
	 *************************************************************************/
	public function toArray( ) {
		return $this->getArrayCopy( );
	}

	public function setOptions( ) {
		$options = func_get_args( );
		if ( in_array( \Pixel418\Iniliq::DISABLE_DEEP_SELECTORS, $options, TRUE ) ) {
			$this->deepSelectorOption = FALSE;
		} else if ( in_array( \Pixel418\Iniliq::ENABLE_DEEP_SELECTORS, $options, TRUE ) ) {
			$this->deepSelectorOption = TRUE;
		}
		if ( in_array( \Pixel418\Iniliq::ERROR_AS_EXCEPTION, $options, TRUE ) ) {
			$this->errorStrategy = \Pixel418\Iniliq::ERROR_AS_EXCEPTION;
		} else if ( in_array( \Pixel418\Iniliq::ERROR_AS_PHPERROR, $options, TRUE ) ) {
			$this->errorStrategy = \Pixel418\Iniliq::ERROR_AS_PHPERROR;
		} else {
			$this->errorStrategy = \Pixel418\Iniliq::ERROR_AS_QUIET;
		}
	}



	/*************************************************************************
	  PROTECTED METHODS				   
	 *************************************************************************/
	protected function undefinedIndexAcces( $index ) {
		if ( $this->errorStrategy == \Pixel418\Iniliq::ERROR_AS_EXCEPTION ) {
			throw new UndefinedIndexException( 'Undefined index: ' . $index );
		} else if ( $this->errorStrategy == \Pixel418\Iniliq::ERROR_AS_PHPERROR ) {
			trigger_error( 'Undefined index: ' . $index );
		}
	}
}

class UndefinedIndexException extends \Exception { }