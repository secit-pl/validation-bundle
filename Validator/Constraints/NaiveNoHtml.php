<?php

declare(strict_types=1);

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\ExpressionLanguage\Expression as ExpressionObject;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\LogicException;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Tomasz Gemza
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class NaiveNoHtml extends Constraint
{
    public const HTML_FOUND_ERROR = '78858d72-5140-410a-9c54-0203fcbee54f';

    protected static $errorNames = [
        self::HTML_FOUND_ERROR => 'XSS_FOUND_ERROR',
    ];

    public string $message = 'No HTML allowed';
    public bool $strongValidation = true;

    public function __construct(
        ?bool $strongValidation = null,
        ?string $message = null,
        mixed $options = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        $this->message = $message ?? $this->message;
        $this->strongValidation = $strongValidation ?? $this->strongValidation;

        parent::__construct($options, $groups, $payload);
    }
}
