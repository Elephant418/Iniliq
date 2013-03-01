<?php

/* This file is part of the Iniliq project, which is under MIT license */

namespace Pixel418\Iniliq;


class Parser {


	/*************************************************************************
	  ATTRIBUTES				   
	 *************************************************************************/
	protected $jsonValuesOption = TRUE;
	protected $deepSelectorOption = TRUE;
	protected $appendSelectorOption = TRUE;
	protected $arrayObjectOption = TRUE;
	protected $errorStrategy = 'quiet';



	/*************************************************************************
	  PUBLIC METHODS				   
	 *************************************************************************/
	public function parse( $files, $initialize = array( ) ) {
		\UArray::doConvertToArray( $files );
		$result = array( );
		if ( is_array( $initialize ) ) {
			array_unshift( $files, $initialize );
		}
		foreach ( $files as $file ) {
			$parsed = $this->parseIni( $file );
			if ( $this->jsonValuesOption ) {
				$this->rewriteJsonValues( $parsed );
			}
			if ( $this->deepSelectorOption ) {
				$this->rewriteDeepSelectors( $parsed );
			}
			$this->mergeValues( $result, $parsed );
			if ( $this->appendSelectorOption ) {
				$this->rewriteAppendingSelectors( $result );
			}
		}
		if ( $this->arrayObjectOption ) {
			$options = array( $this->errorStrategy );
			if ( ! $this->deepSelectorOption ) {
				$options[ ] = \Pixel418\Iniliq::DISABLE_DEEP_SELECTORS;
			}
			$result = new ArrayObject( $result, $options );
		}
		return $result;
	}


	/*************************************************************************
	  CONSTRUCTOR METHODS				   
	 *************************************************************************/
	public function __construct( $options = array( ) ) {
		\UArray::doConvertToArray( $options );
		if ( in_array( \Pixel418\Iniliq::DISABLE_JSON_VALUES, $options, TRUE ) ) {
			$this->jsonValuesOption = FALSE;
			echo 'bou';
		}
		if ( in_array( \Pixel418\Iniliq::DISABLE_DEEP_SELECTORS, $options, TRUE ) ) {
			$this->deepSelectorOption = FALSE;
		}
		if ( in_array( \Pixel418\Iniliq::DISABLE_APPEND_SELECTORS, $options, TRUE ) ) {
			$this->appendSelectorOption = FALSE;
		}
		if ( in_array( \Pixel418\Iniliq::RESULT_AS_ARRAY, $options, TRUE ) ) {
			$this->arrayObjectOption = FALSE;
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
	protected function parseIni( $file ) {
		$parsed = array( );
		if ( is_array( $file ) ) {
			$parsed = $file;
		} else if ( \UString::has( $file, PHP_EOL ) ) {
			$parsed = parse_ini_string( $file, TRUE );
			// TODO: catch error
		} else {
			if ( is_file( $file ) ) {
				$parsed = parse_ini_file( $file, TRUE );
			} else if ( $this->errorStrategy == \Pixel418\Iniliq::ERROR_AS_EXCEPTION ) {
				// TODO: throw a better exception
				throw new \Exception( 'File "' . $file . '" not found' );
			} else if ( $this->errorStrategy == \Pixel418\Iniliq::ERROR_AS_PHPERROR ) {
				trigger_error( 'File "' . $file . '" not found' );
			}
		}
		return $parsed;
	}

	protected function rewriteJsonValues( &$values ) {
		if ( is_array( $values ) ) {
			foreach ( $values as $key => &$value ) {
				if ( ! is_array( $value ) && \UString::isStartWith( $value, array( '[', '{' ) ) ) {
					$json = preg_replace( array( '/([\[\]\{\}:,])\s*(\w)/', '/(\w)\s*([\[\]\{\}:,])/' ), '\1"\2', $value );
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
				if ( \Pixel418\Iniliq::isDeepSelector( $key ) ) {
					$values = \Pixel418\Iniliq::setDeepSelector( $values, $key, $value );
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