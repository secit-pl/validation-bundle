# Symfony Validation Bundle

Additional validators set for Symfony 4.x.

## Installation

From the command line run

```
$ composer require secit-pl/validation-bundle
```

## Validators


### FileExtension

This validator checks if file has valid file extension.

Example usage

```php
use SecIT\ValidationBundle\Validator\Constraints as SecITAssert;

// ...

/**
 * @SecITAssert\FileExtension({"jpg", "jpeg", "png"})
 */
```


```php
use SecIT\ValidationBundle\Validator\Constraints as SecITAssert;

// ...

/**
 * @SecITAssert\FileExtension(disallowedExtensions={"php", "exe", "com"})
 */
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

/**
 * @Assert\Image(
 *      maxSize="2M",
 *      mimeTypes={"image/jpg", "image/jpeg", "image/png"}
 * )
 *
 * @SecITAssert\FileExtension({"jpg", "jpeg", "png"})
 */
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

/**
 * @SecITAssert\CollectionOfUniqueElements()
 */
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
    /**
     * {@inheritdoc}
     */
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

/**
 * @SecITAssert\AntiXss()
 */
 private $text;
```

### NaiveNoHtml

Perform very naive check if text contains HTML.

```php
use SecIT\ValidationBundle\Validator\Constraints as SecITAssert;

// ...

/**
 * @SecITAssert\NaiveNoHtml()
 */
 private $text;
```

### BurnerEmail

Checks if email address is a throw away email addresses (burner email).
This check is perform against the list provided by [wesbos/burner-email-providers](https://github.com/wesbos/burner-email-providers).
You need to install this package manually (`composer require wesbos/burner-email-providers`) if you'd like to use this validator.

```php
use SecIT\ValidationBundle\Validator\Constraints as SecITAssert;

// ...

/**
 * @SecITAssert\BurnerEmail()
 */
 private $email;
```
