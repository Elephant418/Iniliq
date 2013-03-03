Iniliq Documentation
====================

Options
-------

### How to set options 

```php
$parser = new \Pixel418\Iniliq\Parser( \Pixel418\Iniliq::DISABLE_JSON_VALUES, \Pixel418\Iniliq::DISABLE_DEEP_SELECTORS );
$ini = $parser->parse( 'some-file.ini' );
$parser->setOptions( \Pixel418\Iniliq::ENABLE_JSON_VALUES, \Pixel418\Iniliq::DISABLE_APPEND_SELECTORS );
$ini = $parser->parse( 'antoher-file.ini' );
```

### Disable features

All the features are enabled by default. You can disable some of them:

 * <code>\Pixel418\Iniliq::ENABLE_JSON_VALUES</code> or <code>\Pixel418\Iniliq::DISABLE_JSON_VALUES</code> on Parser
 * <code>\Pixel418\Iniliq::ENABLE_DEEP_SELECTORS</code> or <code>\Pixel418\Iniliq::DISABLE_DEEP_SELECTORS</code>: on Parser & ArrayObject
 * <code>\Pixel418\Iniliq::ENABLE_APPEND_SELECTORS</code> or <code>\Pixel418\Iniliq::DISABLE_APPEND_SELECTORS</code>: on Parser
 * <code>\Pixel418\Iniliq::RESULT_AS_ARRAY_OBJECT</code> or <code>\Pixel418\Iniliq::RESULT_AS_ARRAY</code>: on Parser

### Manage error strategy

The availables strategies are :

 * <code>\Pixel418\Iniliq::ERROR_AS_QUIET</code> (default)
 * <code>\Pixel418\Iniliq::ERROR_AS_EXCEPTION</code>
 * <code>\Pixel418\Iniliq::ERROR_AS_PHPERROR</code>

The errors case are :

 * Parser: try to parse an unfound file 
 * ArrayObject: try to get an unknown index
 * ArrayObject: try to unset an unknown index



