<?php

/* This file is part of the Iniliq project, which is under MIT license */

namespace Pixel418;

class Iniliq {


	/*************************************************************************
	  CONSTANTS				   
	 *************************************************************************/
	const VERSION  = '0.3.1';
	const ENABLE_JSON_VALUES = 1;
	const DISABLE_JSON_VALUES = 2;
	const ENABLE_DEEP_SELECTORS = 3;
	const DISABLE_DEEP_SELECTORS = 4;
	const ENABLE_APPEND_SELECTORS = 5;
	const DISABLE_APPEND_SELECTORS = 6;
	const RESULT_AS_ARRAY_OBJECT = 7;
	const RESULT_AS_ARRAY = 8;
	const ERROR_AS_QUIET = 9;
	const ERROR_AS_EXCEPTION = 10;
	const ERROR_AS_PHPERROR = 11;


	/*************************************************************************
	  STATIC METHODS				   
	 *************************************************************************/
	public static function isDeepSelector( $selector ) {
		return ( \UString::has( $selector, '.' ) );
	}
}