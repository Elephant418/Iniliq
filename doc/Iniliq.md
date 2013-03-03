Iniliq Documentation
====================

1. [Options](#options)  
1.1 [Parser options](#parser-options)  
1.2 [ArrayObject options](#arrayobject-options)  
1.3 [Errors cases](#error-cases)  


Options
-------

### How to set options 

By giving arguments to the constructor:
```php
$parser = new \Pixel418\Iniliq\Parser( \Pixel418\Iniliq::DISABLE_JSON_VALUES, \Pixel418\Iniliq::DISABLE_DEEP_SELECTORS );
$ini = $parser->parse( 'some-file.ini' );
```

Or use setOptions method:
```php
$parser = new \Pixel418\Iniliq\Parser;
$parser->setOptions( \Pixel418\Iniliq::DISABLE_JSON_VALUES, \Pixel418\Iniliq::DISABLE_DEEP_SELECTORS );
$ini = $parser->parse( 'some-file.ini' );
```


### Parser options

*   Json values:
    * <code>\Pixel418\Iniliq::ENABLE_JSON_VALUES</code> (default)
    * <code>\Pixel418\Iniliq::DISABLE_JSON_VALUES</code>
<br>

*   Deep selectors:
    * <code>\Pixel418\Iniliq::ENABLE_DEEP_SELECTORS</code> (default)
    * <code>\Pixel418\Iniliq::DISABLE_DEEP_SELECTORS</code>
<br> 

*   Appending & removing array values selectors:
    * <code>\Pixel418\Iniliq::ENABLE_APPEND_SELECTORS</code> (default)
    * <code>\Pixel418\Iniliq::DISABLE_APPEND_SELECTORS</code>
<br>

*   Result format:
    * <code>\Pixel418\Iniliq::RESULT_AS_ARRAY_OBJECT</code> (default)
    * <code>\Pixel418\Iniliq::RESULT_AS_ARRAY</code>
<br>

*   Error strategy:
    * <code>\Pixel418\Iniliq::ERROR_AS_QUIET</code> (default)
    * <code>\Pixel418\Iniliq::ERROR_AS_EXCEPTION</code>
    * <code>\Pixel418\Iniliq::ERROR_AS_PHPERROR</code>
<br>


### ArrayObject options

If you get the ArrayObject by using the Parser, the options are setted with the same values of the Parser.

*   Appending & removing selectors:
    * <code>\Pixel418\Iniliq::ENABLE_APPEND_SELECTORS</code> (default)
    * <code>\Pixel418\Iniliq::DISABLE_APPEND_SELECTORS</code>
<br>

*   Error strategy:
    * <code>\Pixel418\Iniliq::ERROR_AS_QUIET</code> (default)
    * <code>\Pixel418\Iniliq::ERROR_AS_EXCEPTION</code>
    * <code>\Pixel418\Iniliq::ERROR_AS_PHPERROR</code>
<br>


### Errors cases

 * Parser: Try to parse an unfound file 
 * ArrayObject: Try to get an unknown index
 * ArrayObject: Try to unset an unknown index
