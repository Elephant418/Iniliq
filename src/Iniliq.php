<?php

/* This file is part of the Iniliq project, which is under MIT license */

namespace Pixel418;


class Iniliq {

    const VERSION = '0.1.0';


    /*************************************************************************
      PUBLIC METHODS                   
     *************************************************************************/
    public function parse( $files, $initialize = [ ] ) {
        \UArray\do_convert_to_array( $files );
        $result = $initialize;
        foreach ( $files as $file ) {
            $parsed = $this->parse_ini( $file );
            $this->rewrite_json_values( $parsed );
            $this->rewrite_array_selectors( $parsed );
            $this->merge_values( $result, $parsed );
        }
        return $result;
    }


    /*************************************************************************
      PROTECTED METHODS                   
     *************************************************************************/
    protected function rewrite_json_values( &$values ) {
        foreach ( $values as $key => &$value ) {
            if ( ! is_array( $value ) && \UString\is_start_with( $value, [ '[', '{' ] ) ) {
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
    
    protected function rewrite_array_selectors( &$values ) {
        foreach ( $values as $key => &$value ) {
            if ( is_array( $value ) ) {
                $this->rewrite_array_selectors( $value );
            }
            if ( \UString\has( $key, '.' ) ) {
                $path = explode( '.', $key );
                $current =& $values;
                while ( ( $current_key = array_shift( $path ) ) ) {
                    if ( ! isset( $current[ $current_key ] ) ) {
                        $current[ $current_key ] = [ ];
                    }
                    $current =& $current[ $current_key ];
                }
                $current = $value;
                unset( $values[ $key ] );
            }
        }
    }

    protected function parse_ini( $file ) {
        $parsed = [ ];
        if ( \UString\has( $file, PHP_EOL ) ) {
                $parsed = parse_ini_string( $file, TRUE );
        } else {
            $parsed = parse_ini_file( $file, TRUE );
        }
        return $parsed;
    }

    protected function merge_values( &$reference, $values ) {
        foreach ( $values as $key => $value ) {
            if ( preg_match( '/\s*\+\s*$/', $key ) > 0 ) {
                $key = preg_replace( '/\s*\+\s*$/', '', $key );
                $this->append_values( $reference[ $key ], $value );
            } else if ( preg_match( '/\s*-\s*$/', $key ) > 0 ) {
                $key = preg_replace( '/\s*-\s*$/', '', $key );
                $this->remove_value( $reference[ $key ], $value );
            } else if ( isset( $reference[ $key ] ) && is_array( $value ) ) {
                $this->merge_values( $reference[ $key ], $value );
            } else {
                $this->replace_value( $reference[ $key ], $value );
            }
        }
    }

    protected function append_values( &$reference, $values ) {
        \UArray\do_convert_to_array( $reference );
        \UArray\do_convert_to_array( $values );
        foreach ( $values as $key => $value ) {
            if ( is_numeric( $key ) ) {
                $reference[ ] = $value;
            } else {
                $reference[ $key ] = $value;
            }
        }
    }

    protected function remove_value( &$reference, $values ) {
        \UArray\do_convert_to_array( $reference );
        \UArray\do_convert_to_array( $values );
        foreach ( $values as $value ) {
            $keys = array_keys( $reference, $value );
            foreach( $keys as $key ) {
                if ( $key !== FALSE ) {
                    if ( is_numeric( $key ) ) {
                        array_splice( $reference, $key, 1 );
                    } else {
                        unset( $reference[ $key ] );
                    }
                }
            }
        }
    }

    protected function replace_value( &$reference, $value ) {
        $reference = $value;
    }
}

if ( ! defined( 'Pixel418\\VENDOR_ROOT_PATH' ) ) {
    if ( $pos = strrpos( __DIR__, '/vendor/' ) ) {
        define( 'Pixel418\\VENDOR_ROOT_PATH', substr( __DIR__, 0, $pos ) . '/vendor/' );
    }
}
require_once( \Pixel418\VENDOR_ROOT_PATH . 'pixel418/ubiq/ubiq/Ubiq.php' );