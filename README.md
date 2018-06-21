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

Parameters

| Parameter | Type | Default | Description |
|---|---|---|---| 
| validExtensions | array | empty array | Allowed/valid file extensions list |
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
```
 