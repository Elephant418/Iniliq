Iniliq [![Build Status](https://secure.travis-ci.org/Pixel418/Iniliq.png)](http://travis-ci.org/Pixel418/Iniliq)
======

An ini parser for inherited values through multiple configuration files

1. [Let's code](#lets-code)
1.1 [Json Values](#json-values)
1.2 [Deep selectors](#deep-selectors)
1.3 [File inheritance](#file-inheritance)
1.4 [Adding values](#adding-values)
1.5 [Removing values](#removing-values)
2. [How to Install](#how-to-install)
3. [How to Contribute](#how-to-contribute)
4. [Author & Community](#author--community)



Let's code
-------- 

### Json values

```ini
// json-values.ini
[Readme]
example = { json: yeah, is-it: [ good, great, awesome ] }
```

```php
$ini = ( new \Pixel418\Iniliq )->parse( 'json-values.ini' );
// Returns [ 'Readme' => [ 'example' => [ 'json' => 'yeah', 'is-it' => [ 'good', 'great', 'awesome' ] ] ] ]
```

### Deep selectors

```ini
// deep-selectors.ini
[Readme]
example.selectors.deep = nice
```

```php
$ini = ( new \Pixel418\Iniliq )->parse( 'deep-selectors.ini' );
// Returns [ 'Readme' => [ 'example' => [ 'selectors' => [ 'deep' => 'nice' ] ] ]
```

### File inheritance

```ini
// base.ini
[Readme]
example[name] = John Doe
example[id] = 3

// file-inheritance.ini
[Readme]
example.name = file-inheritance
```

```php
$ini = ( new \Pixel418\Iniliq )->parse( [ 'base.ini', 'file-inheritance.ini' ] );
// Returns [ 'Readme' => [ 'example' => [ 'name' => 'file-inheritance', 'id' => '3' ] ] ]
```

### Adding values

```ini
// list.ini
[Readme]
musketeers.name[ ] = Athos
musketeers.name[ ] = Porthos
musketeers.name[ ] = "D'Artagnan"

// adding-values.ini
[Readme]
musketeers.name += [ Aramis ]
```

```php
$ini = ( new \Pixel418\Iniliq )->parse( [ 'list.ini', 'adding-values.ini' ] );
// Returns [ 'Readme' => [ 'musketeers' => [ 'Athos', 'Porthos', 'D\'Artagnan', 'Aramis' ] ] ]
```

### Removing values

```ini
// list.ini
[Readme]
musketeers.name[ ] = Athos
musketeers.name[ ] = Porthos
musketeers.name[ ] = "D'Artagnan"

// removing-values.ini
[Readme]
musketeers.name -= "[ D'Artagnan ]"
```

```php
$ini = ( new \Pixel418\Iniliq )->parse( [ 'list.ini', 'removing-values.ini' ] );
// Returns [ 'Readme' => [ 'musketeers' => [ 'Athos', 'Porthos' ] ] ]
```

[&uarr; top](#readme)



How to Install
--------

If you don't have composer, you have to [install it](http://getcomposer.org/doc/01-basic-usage.md#installation).  

Add or complete the composer.json file at the root of your project, like this :

```json
{
    "require": {
        "pixel418/iniliq": "0.1.1"
    }
}
```

Iniliq can now be [downloaded via composer](http://getcomposer.org/doc/01-basic-usage.md#installing-dependencies).

The last step is to include it in your PHP file :

```php
require_once( './vendor/pixel418/iniliq/src/Iniliq.php' );
```

[&uarr; top](#readme)



How to Contribute
--------

1. Fork the Iniliq repository
2. Create a new branch for each feature or improvement
3. Send a pull request from each feature branch to the **develop** branch

If you don't know much about pull request, you can read [the Github article](https://help.github.com/articles/using-pull-requests).

All pull requests must follow this particular [style guide](https://github.com/Pixel418/Style_Guide) and accompanied by passing [phpunit](https://github.com/sebastianbergmann/phpunit/) tests.

[&uarr; top](#readme)



Author & Community
--------

Iniliq is under the [MIT License](http://opensource.org/licenses/MIT).  
It is created and maintained by [Thomas ZILLIOX](http://zilliox.me).

[&uarr; top](#readme)
