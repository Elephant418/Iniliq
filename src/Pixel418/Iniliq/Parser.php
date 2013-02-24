<?php

/* This file is part of the Iniliq project, which is under MIT license */

namespace Pixel418\Iniliq;


class Parser {


	/*************************************************************************
	  PUBLIC METHODS				   
	 *************************************************************************/
	public function parse( $files, $initialize = [ ] ) {
		\UArray::doConvertToArray( $files );
		$result = [ ];
		if ( is_array( $initialize ) ) {
			array_unshift( $files, $initialize );
		}
		foreach ( $files as $file ) {
			$parsed = $this->parseIni( $file );
			$this->rewriteJsonValues( $parsed );
			$this->rewriteDeepSelectors( $parsed );
			$this->mergeValues( $result, $parsed );
			$this->rewriteAppendingSelectors( $result );
		}
		return new ArrayObject( $result );
	}


	/*************************************************************************
	  PROTECTED METHODS				   
	 *************************************************************************/
	protected function parseIni( $file ) {
		$parsed = [ ];
		if ( is_array( $file ) ) {
			$parsed = $file;
		} else if ( \UString::has( $file, PHP_EOL ) ) {
			$parsed = parse_ini_string( $file, TRUE );
		} else if ( is_file( $file ) ) {
			$parsed = parse_ini_file( $file, TRUE );
		}
		return $parsed;
	}

	protected function rewriteJsonValues( &$values ) {
		if ( is_array( $values ) ) {
			foreach ( $values as $key => &$value ) {
				if ( ! is_array( $value ) && \UString::isStartWith( $value, [ '[', '{' ] ) ) {
					$json = preg_replace( [ '/([\[\]\{\}:,])\s*(\w)/', '/(\w)\s*([\[\]\{\}:,])/' ], '\1"\2', $value );
					if ( $array = json_decode( $json, TRUE ) ) {
						$value = $array;
					}
				}
				if ( is_array( $value ) ) {
					$this->rewriteJsonValues( $value );
				}
			}
		}
	}

	protected function rewriteDeepSelectors( &$values ) {
		if ( is_array( $values ) ) {
			foreach ( $values as $key => &$value ) {
				if ( is_array( $value ) ) {
					$this->rewriteDeepSelectors( $value );
				}
				if ( \Pixel418\Iniliq::is_deep_selector( $key ) ) {
					$values = \Pixel418\Iniliq::set_deep_selector( $values, $key, $value );
					unset( $values[ $key ] );
				}
			}
		}
	}

	protected function mergeValues( &$reference, $values ) {
		if ( is_array( $values ) ) {
			foreach ( $values as $key => $value ) {
				if ( isset( $reference[ $key ] ) && is_array( $value ) ) {
					$this->mergeValues( $reference[ $key ], $value );
				} else {
					$this->mergeValuesByReplacing( $reference[ $key ], $value );
				}
			}
		}
	}

	protected function mergeValuesByReplacing( &$reference, $value ) {
		$reference = $value;
	}

	protected function rewriteAppendingSelectors( &$values ) {
		if ( is_array( $values ) ) {
			foreach ( $values as $key => &$value ) {
				if ( preg_match( '/\s*\+\s*$/', $key ) > 0 ) {
					$this->appendingValues( $values, $key );
				} else if ( preg_match( '/\s*-\s*$/', $key ) > 0 ) {
					$this->removingValues( $values, $key );
				}
				if ( is_array( $value ) ) {
					$this->rewriteAppendingSelectors( $value );
				}
			}
		}
	}

	protected function appendingValues( &$values, $key ) {
		$reference_key = preg_replace( '/\s*\+\s*$/', '', $key );
		$reference =& $values[ $reference_key ];
		$append = $values[ $key ];
		unset( $values[ $key ] );
		\UArray::doConvertToArray( $reference );
		\UArray::doConvertToArray( $append );
		foreach ( $append as $key => $value ) {
			if ( is_numeric( $key ) ) {
				$reference[ ] = $value;
			} else {
				$reference[ $key ] = $value;
			}
		}
	}

	protected function removingValues( &$values, $key ) {
		$reference_key = preg_replace( '/\s*-\s*$/', '', $key );
		$reference =& $values[ $reference_key ];
		$remove = $values[ $key ];
		unset( $values[ $key ] );
		\UArray::doConvertToArray( $reference );
		\UArray::doRemoveValue( $reference, $remove );		
	}
}