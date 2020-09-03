<?php

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class NaiveNoHtml.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Tomasz Gemza
 */
class NaiveNoHtml extends Constraint
{
    public const HTML_FOUND_ERROR = '78858d72-5140-410a-9c54-0203fcbee54f';

    protected static $errorNames = [
        self::HTML_FOUND_ERROR => 'XSS_FOUND_ERROR',
    ];

    /**
     * @var string
     */
    public $message = 'No HTML allowed';

    /**
     * @var bool
     */
    public $strongValidation = true;
}
