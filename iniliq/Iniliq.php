<?php

/* This file is part of the Iniliq project, which is under MIT license */

namespace Pixel418;


class Iniliq {

    const VERSION = '0.1.0';


    /*************************************************************************
      PUBLIC METHODS                   
     *************************************************************************/
    public function parse( $files, $initialize = [ ] ) {
        \UArray\must_be_array( $files );
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
        if ( \UString\contains( $file, PHP_EOL ) ) {
                $parsed = parse_ini_string( $file, TRUE );
        } else {
            $parsed = parse_ini_file( $file, TRUE );
        }
        return $parsed;
    }

    protected function merge_values( &$reference, $add ) {
        foreach ( $add as $key => $value ) {
            if ( is_numeric( $key ) ) {
                $reference[ ] = $value;
            } else if ( isset( $reference[ $key ] ) && is_array( $value ) ) {
                $this->merge_values( $reference[ $key ], $value );
            } else {
                $reference[ $key ] = $value;
            }
        }
    }
}

if ( ! defined( 'Pixel418\\VENDOR_ROOT_PATH' ) ) {
    if ( $pos = strrpos( __DIR__, '/vendor/' ) ) {
        define( 'Pixel418\\VENDOR_ROOT_PATH', substr( __DIR__, 0, $pos ) . '/vendor/' );
    }
}
require_once( \Pixel418\VENDOR_ROOT_PATH . 'pixel418/ubiq/ubiq/Ubiq.php' );