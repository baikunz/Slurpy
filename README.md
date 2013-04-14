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
Each call to factory methods returns a Slurpy instance, then you just need to call the `generate`
method on this Slurpy instance to process the operation.

```php
<?php

// Create a new factory instance, give it your path to pdftk binary
$factory = new \Shuble\Slurpy\Factory('/path/to/pdftk');

$slurpy = $factory->generateFdf('path/to/input.pdf', 'path/to/output.fdf');

$slurpy->generate();
```

### Catenate PDF files

The `cat` operation assembles pages from input PDFs to create a new PDF. Use cat to merge PDF pages
or to split PDF pages from documents. You can also use it to rotate PDF pages. Page order in the
new PDF is specified by the order of the inputs array.

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

$slurpy = $factory->cat($inputs, $output);
```

Now, `/path/to/ouput.pdf` contains the 3 pdfs, with only odd pages rotated to east for the third pdf. 

### Shuffle PDF files

The `shuffle` operation collates pages from input PDFs to create a new PDF. Works like the cat
operation except that it takes one page at a time from each page range to assemble the output PDF.
If one range runs out of pages, it continues with the remaining ranges. This feature was designed
to help collate PDF pages after scanning paper documents.

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
```

### Background

The `background` operation Applies a PDF watermark to the background of a single input PDF.
It uses only the first page from the background PDF and applies it to every page of the input PDF.
This page is scaled and rotated as needed to fit the input page.
If the input PDF does not have a transparent background (such as a PDF created from page scans)
then the resulting background won’t be visible — use the stamp operation instead.

You can also pass a fourth parameter `$multi` if you want to use the multibackground operation.

`multibackground` is the same as the background operation, but applies each page of the
background PDF to the corresponding page of the input PDF. If the input PDF has more
pages than the stamp PDF, then the final stamp page is repeated across these remaining
pages in the input PDF.

```php
<?php

$input = '/path/to/input.pdf'; // or array('filepath' => '/path/to/input.pdf', 'password' => 'S3cr3t');
$background = '/path/to/background.pdf';
$output = '/path/to/output.pdf';
$multi = false // [Default], Or true for multibackground operation

// Creates a Slurpy instance
$slurpy = $factory->background($input, $background, $output, $multi);
```

### Burst

The `burst` operation splits a single, input PDF document into individual pages. Naming for the
resulting page is specified if a printf-style format string. i.e. if you give an ouput of
`page_%02d.pdf` to Slurpy, resulting pages are going to be `page_01.pdf`, `page_02.pdf` and so on.
> NOTE that you may have to give `output` as a complete path such as `/path/to/pdfs/page_%04d.pdf`

```php
<?php

$input = '/path/to/input.pdf'; // or array('filepath' => '/path/to/input.pdf', 'password' => 'S3cr3t');
$output = '/path/to/folder/pg_%02d.pdf';

$slurpy = $factory->burst($input, $output);
```

### Generate fdf

The `generateFdf` operation reads a single, input PDF file and generates an FDF file suitable for `fillForm`
operation. It saves this FDF file using the output filename.

```php
<?php

$input = '/path/to/input.pdf'; // or array('filepath' => '/path/to/input.pdf', 'password' => 'S3cr3t');
$output = '/path/to/folder/output.fdf';

$slurpy = $factory->generateFdf($input, $output);
```

### Fill form

The `fillForm` operation fills a single input PDF form with the given fdf or xfdf data file.
By default after filling the form, the ouput pdf fields remains active. You can disable the fields
from the output pdf by giving `true` as a fourth argument which will `flatten` the resulting
pdf.
> NOTE That Slurpy will soon be able to fill forms with simple key/value pairs as data instead of
fdf or xfdf files.

```php
<?php

$input = '/path/to/input.pdf'; // or array('filepath' => '/path/to/input.pdf', 'password' => 'S3cr3t');
$data = '/path/to/data.fdf'; // or data.xfdf
$output = '/path/to/folder/output.fdf';

$slurpy = $factory->fillForm($input, $data, $output);

// Or to flatten the resulting pdf.
$slurpy = $factory->fillForm($input, $data, $output, true);
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