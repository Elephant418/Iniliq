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
            $this->merge_values( $result, $parsed );
            // Merge keys
        }
        return $result;
    }


    /*************************************************************************
      PROTECTED METHODS                   
     *************************************************************************/
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
            $pos = array_search( $value, $reference );
            if ( $pos !== FALSE ) {
                unset( $reference[ $pos ] );
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