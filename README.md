# Slurpy

Slurpy is a PHP5 wrapper for the [pdftk](http://www.pdflabs.com/tools/pdftk-the-pdf-toolkit/) command-line tool
for working with PDF. This library is largely inspired by [Snappy](https://github.com/KnpLabs/snappy) from KnpLabs, 
a library for generating images or pdf from html. Some of the Slurpy code comes directly from Snappy.

In order to use Slurpy you will have to download [pdftk](http://www.pdflabs.com/tools/pdftk-the-pdf-toolkit/) for
either Windows, Mac OSX or linux.

[![Build Status](https://secure.travis-ci.org/baikunz/Slurpy.png?branch=master)](http://travis-ci.org/baikunz/Slurpy)

## Installation

If you are using [composer](http://getcomposer.org) add the following lines to your `composer.json`:

```json
{
    "require" :  {
        "shuble/slurpy": "*"
    }
}
```

And run these commands:

```
wget http://getcomposer.org/composer.phar
php composer.phar install
```

Now, just add the autoloader:

```php
<?php

require 'vendor/autoload.php';
```

Or, if you are not using composer, require the provided autoload:
```php
<?php

require 'path/to/slurpy/src/autoload.php';
```

Then proceed with the installation of pdftk if not already installed. Please refer to the 
[pdftk](http://www.pdflabs.com/tools/pdftk-the-pdf-toolkit/) website, and grab the installer
or sources, depending on your system.

That's it, you're done.

## Simple usage

Visit the [pdftk documentation](http://www.pdflabs.com/docs/pdftk-man-page/) for more details
about each operations.

### Create the factory

Slurpy comes with a simple factory for dealing with pdftk operations in their simple forms.

```php
<?php

// Create a new factory instance, give it your path to pdftk binary
$factory = new \Shuble\Slurpy\Factory('/path/to/pdftk');
```

### Catenate PDF files

Assembles ("catenates") pages from input PDFs to create a new PDF.
Use cat to merge PDF pages or to split PDF pages from documents.
You can also use it to rotate PDF pages. Page order in the new PDF
is specified by the order of the inputs array.

```php
<?php

use Shuble\Slurpy\Operation\OperationArgument\PageRange;

$inputs = array(
    '/path/to/file1.pdf',
    '/path/to/file2.pdf',
    array(
        'filepath'   => '/path/to/file3.pdf',
        'password'   => 'pa$$word',
        'start_page' => 1,
        'end_page'   => 'end',
        'qualifier'  => PageRange::QUALIFIER_ODD,
        'rotation'   => PageRange::ROTATION_EAST,
    )
);

$output = '/path/to/output.pdf';

// Creates a Slurpy instance
$slurpy = $factory->cat($inputs, $output);

// Run the operation
$slurpy->generate();
```

Now, `/path/to/ouput.pdf` contains the 3 pdfs, with only odd pages rotated to east for the third pdf. 

### Shuffle PDF files

Collates pages from input PDFs to create a new PDF. Works like the cat operation except that it takes
one page at a time from each page range to assemble the output PDF. If one range runs out of pages,
it continues with the remaining ranges. This feature was designed to help collate PDF pages after
scanning paper documents.

```php
<?php

use Shuble\Slurpy\Operation\OperationArgument\PageRange;

$inputs = array(
    '/path/to/file1.pdf',
    '/path/to/file2.pdf',
    array(
        'filepath'   => '/path/to/file3.pdf',
        'password'   => 'pa$$word',
        'start_page' => 1,
        'end_page'   => 'end',
        'qualifier'  => PageRange::QUALIFIER_ODD,
        'rotation'   => PageRange::ROTATION_EAST,
    )
);

$output = '/path/to/output.pdf';

// Creates a Slurpy instance
$slurpy = $factory->shuffle($inputs, $output);

// Run the operation
$slurpy->generate();
```

## Unit tests

Run the following command:
```
phpunit
```
Done.

## Credits

- dorian ALKOUM - baikunz@gmail.com

## License

Slurpy is released under the MIT license. See the provided LICENSE file for more details.