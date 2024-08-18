# Symfony Validation Bundle

Additional validators set for Symfony.

## Compatibility matrix


| Bundle version | Maintained | Symfony versions | Min. PHP version |
|----------------|------------|------------------|------------------|
| 3.x            | Yes        | 7.x              | 8.2.0            |
| 2.x            | No         | 6.x              | 8.0.0            |
| 1.8            | No         | 5.x, 4.x         | 7.1.0            |


## Installation

From the command line run

```
$ composer require secit-pl/validation-bundle
```

## Validators

### NotBlankIf

This validator checks if value is not blank like a standard NotBlank Symfony validator, but also allows define 
the condition when the NotBlank validation should be performed using Symfony Expression Language.


> From Symfony 6.2 you can also use When validator.
>
> https://symfony.com/blog/new-in-symfony-6-2-conditional-constraints
>
> https://symfony.com/doc/6.2/reference/constraints/When.html

Example usage

```php
use SecIT\ValidationBundle\Validator\Constraints as SecITAssert;

// ...

#[SecITAssert\NotBlankIf("this.isSuperUser")]
private ?string $email = null;

public function isSuperUser(): bool
{
    return true;
}
```

Parameters

| Parameter | Type | Default | Description |
|---|---|---|---| 
| expression | string | empty array | The expression that will be evaluated. If the expression evaluates to a false value (using ==, not ===), not blank validation won't be performed) |
| values | array | empty array | The values of the custom variables used in the expression. Values can be of any type (numeric, boolean, strings, null, etc.) |


### FileExtension

This validator checks if file has valid file extension.


> From Symfony 6.2 you can also use the "extensions" option in File validator.
>
> https://symfony.com/blog/new-in-symfony-6-2-improved-file-validator
>
> https://symfony.com/doc/6.2/reference/constraints/File.html#extensions



Example usage

```php
use SecIT\ValidationBundle\Validator\Constraints as SecITAssert;

// ...

#[SecITAssert\FileExtension(["jpg", "jpeg", "png"])]
private $file;
```


```php
use SecIT\ValidationBundle\Validator\Constraints as SecITAssert;

// ...

#[SecITAssert\FileExtension(disallowedExtensions: ["jpg", "jpeg", "png"])]
private $file;
```

Parameters

| Parameter | Type | Default | Description |
|---|---|---|---| 
| validExtensions | array | empty array | Allowed/valid file extensions list |
| disallowedExtensions | array | empty array | Disallowed/invalid file extensions list |
| matchCase | bool | false | Enable/disable verifying the file extension case |

**Caution!** It's highly recommended to use this validator together with native Symfony File/Image validator.

```php
use SecIT\ValidationBundle\Validator\Constraints as SecITAssert;
use Symfony\Component\Validator\Constraints as Assert;

// ...

#[Assert\Image(maxSize: '2M', mimeTypes: ["image/jpg", "image/jpeg", "image/png"])]
#[SecITAssert\FileExtension(validExtensions: ["jpg", "jpeg", "png"])]
private $file;
```

### CollectionOfUniqueElements

Checks if collection contains only unique elements.

Parameters

| Parameter | Type | Default | Description |
|---|---|---|---| 
| matchCase | bool | false | Enable/disable verifying the characters case |
| customNormalizationFunction | null or callable | null | Custom normalization function |

```php
use SecIT\ValidationBundle\Validator\Constraints as SecITAssert;

// ...

#[SecITAssert\CollectionOfUniqueElements()]
private $collection;
```

This validator can also be used to validate unique files upload.

```php
<?php

declare(strict_types=1);

namespace App\Form;

use SecIT\ValidationBundle\Validator\Constraints\CollectionOfUniqueElements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class ExampleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('files', CollectionType::class, [
            'entry_type' => FileType::class,
            'allow_add' => true,
            'constraints' => [
                new CollectionOfUniqueElements(),
            ],
        ]);
    }
}

```

### AntiXss

Checks if text contains XSS attack using [voku\anti-xss](https://github.com/voku/anti-xss) library.

```php
use SecIT\ValidationBundle\Validator\Constraints as SecITAssert;

// ...

#[SecITAssert\AntiXss()]
private $text;
```

### NaiveNoHtml

Perform very naive check if text contains HTML.

```php
use SecIT\ValidationBundle\Validator\Constraints as SecITAssert;

// ...

#[SecITAssert\NaiveNoHtml()]
private $text;
```

Parameters

| Parameter | Type | Default | Description |
|---|---|---|---| 
| strongValidation | bool | true (recommended) | Enable/disable strong validation. Disable if you'd like to allow strings with unclosed tags such as "I <3 You". |

### BurnerEmail

Checks if email address is a throw away email addresses (burner email).
This check is perform against the list provided by [wesbos/burner-email-providers](https://github.com/wesbos/burner-email-providers).
You need to install this package manually (`composer require wesbos/burner-email-providers`) if you'd like to use this validator.

```php
use SecIT\ValidationBundle\Validator\Constraints as SecITAssert;

// ...

#[SecITAssert\BurnerEmail()]
private $email;
```

### ClamAvScanFile

Scans file for infection using ClamAV.
The check is made using the bundle [sineflow/clamav](https://github.com/secit-pl/clamav).
You need to install and configure this package manually if you'd like to use this validator.

You can find test files here https://github.com/fire1ce/eicar-standard-antivirus-test-files/tree/master.

> The validator will not work if the PrivateTmp is set to true because the temp file path in php will differ from the real system temp file path so the clamscan will not find the file to scan!

```php
use SecIT\ValidationBundle\Validator\Constraints as SecITAssert;

// ...

#[SecITAssert\ClamAvScanFile()]
private \Symfony\Component\HttpFoundation\File\File $file;
```

## Want to support this bundle?

Consider using our [random code generator](https://codito.io/) service at [codito.io](https://codito.io/).

With [codito.io](https://codito.io/) you can generate up to 250,000 codes in the format of your choice for free. You can use the generated codes for purposes such as promotional codes (which you can, for example, print on the inside of packaging), serial numbers, one-time or multi-use passwords, lottery coupons, discount codes, vouchers, random strings and much more - for more use cases see our [examples](https://codito.io/free-random-code-generator/examples). If 250,000 codes are not enough for you, you can use our [commercial code generation service](https://codito.io/commercial-code-generator/).

[![Random Code Generator](https://codito.io/build/favicons/logo.e56f7fb1.webp)](https://codito.io/)
