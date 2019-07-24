<?php

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class FileExtension.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Tomasz Gemza
 */
class AntiXss extends Constraint
{
    public const XSS_FOUND_ERROR = 'fce1015f-95ed-44e4-b96e-f033c9ff67e3';

    protected static $errorNames = [
        self::XSS_FOUND_ERROR => 'XSS_FOUND_ERROR',
    ];

    /**
     * @var string
     */
    public $message = 'XSS attack detected';
}
