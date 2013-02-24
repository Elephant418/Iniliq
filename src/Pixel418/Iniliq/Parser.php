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
			$parsed = $this->parse_ini( $file );
			$this->rewrite_json_values( $parsed );
			$this->rewrite_deep_selectors( $parsed );
			$this->merge_values( $result, $parsed );
			$this->rewrite_appending_selectors( $result );
		}
		return new ArrayObject( $result );
	}


	/*************************************************************************
	  PROTECTED METHODS				   
	 *************************************************************************/
	protected function parse_ini( $file ) {
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

	protected function rewrite_json_values( &$values ) {
		if ( is_array( $values ) ) {
			foreach ( $values as $key => &$value ) {
				if ( ! is_array( $value ) && \UString::isStartWith( $value, [ '[', '{' ] ) ) {
					$json = preg_replace( [ '/([\[\]\{\}:,])\s*(\w)/', '/(\w)\s*([\[\]\{\}:,])/' ], '\1"\2', $value );
					if ( $array = json_decode( $json, TRUE ) ) {
						$value = $array;
					}
				}
				if ( is_array( $value ) ) {
					$this->rewrite_json_values( $value );
				}
			}
		}
	}

	protected function rewrite_deep_selectors( &$values ) {
		if ( is_array( $values ) ) {
			foreach ( $values as $key => &$value ) {
				if ( is_array( $value ) ) {
					$this->rewrite_deep_selectors( $value );
				}
				if ( \Pixel418\Iniliq::is_deep_selector( $key ) ) {
					$values = \Pixel418\Iniliq::set_deep_selector( $values, $key, $value );
					unset( $values[ $key ] );
				}
			}
		}
	}

	protected function merge_values( &$reference, $values ) {
		if ( is_array( $values ) ) {
			foreach ( $values as $key => $value ) {
				if ( isset( $reference[ $key ] ) && is_array( $value ) ) {
					$this->merge_values( $reference[ $key ], $value );
				} else {
					$this->merge_values_by_replacing( $reference[ $key ], $value );
				}
			}
		}
	}

	protected function merge_values_by_replacing( &$reference, $value ) {
		$reference = $value;
	}

	protected function rewrite_appending_selectors( &$values ) {
		if ( is_array( $values ) ) {
			foreach ( $values as $key => &$value ) {
				if ( preg_match( '/\s*\+\s*$/', $key ) > 0 ) {
					$this->appending_values( $values, $key );
				} else if ( preg_match( '/\s*-\s*$/', $key ) > 0 ) {
					$this->removing_values( $values, $key );
				}
				if ( is_array( $value ) ) {
					$this->rewrite_appending_selectors( $value );
				}
			}
		}
	}

	protected function appending_values( &$values, $key ) {
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

	protected function removing_values( &$values, $key ) {
		$reference_key = preg_replace( '/\s*-\s*$/', '', $key );
		$reference =& $values[ $reference_key ];
		$remove = $values[ $key ];
		unset( $values[ $key ] );
		\UArray::doConvertToArray( $reference );
		\UArray::doRemoveValue( $reference, $remove );		
	}
}