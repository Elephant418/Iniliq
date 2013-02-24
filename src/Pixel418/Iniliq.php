<?php

/* This file is part of the Iniliq project, which is under MIT license */

namespace Pixel418;

class Iniliq {

	const VERSION  = '0.2';

	public static function isDeepSelector( $selector ) {
		return ( \UString::has( $selector, '.' ) );
	}

	public static function hasDeepSelector( $array, $selector ) {
		return static::deepSelectorCallback( $array, $selector, function( &$current, $current_key ) {
			return FALSE;
		}, function ( &$current, $current_key ) use ( &$array ) {
			return TRUE;
		} );
	}

	public static function getDeepSelector( $array, $selector ) {
		return static::deepSelectorCallback( $array, $selector, function( &$current, $current_key ) {
			return NULL;
		}, function ( &$current, $current_key ) use ( &$array ) {
			return $current[ $current_key ];
		} );
	}

	public static function setDeepSelector( $array, $selector, $value ) {
		return static::deepSelectorCallback( $array, $selector, function( &$current, $current_key ) {
			$current[ $current_key ] = [ ];
		}, function ( &$current, $current_key ) use ( &$array, $value ) {
			$current[ $current_key ] = $value;
			return $array;
		} );
	}

	public static function unsetDeepSelector( $array, $selector ) {
		return static::deepSelectorCallback( $array, $selector, function( &$current, $current_key ) use ( &$array ) {
			return $array;
		}, function ( &$current, $current_key ) use ( &$array ) {
			unset( $current[ $current_key ] );
			return $array;
		} );
	}

	public static function deepSelectorCallback( &$array, $selector, $callback_path_not_found, $callback_end ) {
		$path = explode( '.', $selector );
		$current_key = $selector;
		$current =& $array;
		while ( count( $path ) ) {
			$current_key = array_shift( $path );
			if ( ! isset( $current[ $current_key ] ) ) {
				$result = call_user_func_array( $callback_path_not_found, array( &$current, $current_key ) );
				if ( ! isset( $current[ $current_key ] ) ) {
					return $result;
				}
			}
			if ( count( $path ) ) {
				$current =& $current[ $current_key ];
			}
		}
		$result = call_user_func_array( $callback_end, array( &$current, $current_key ) );
		return $result;
	}
}