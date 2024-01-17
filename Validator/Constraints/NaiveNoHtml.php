<?php

declare(strict_types=1);

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Tomasz Gemza
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class NaiveNoHtml extends Constraint
{
    public const HTML_FOUND_ERROR = '78858d72-5140-410a-9c54-0203fcbee54f';

    protected static array $errorNames = [
        self::HTML_FOUND_ERROR => 'XSS_FOUND_ERROR',
    ];

    public function __construct(
        public bool $strongValidation = true,
        public string $message = 'HTML is not allowed',
        mixed $options = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
