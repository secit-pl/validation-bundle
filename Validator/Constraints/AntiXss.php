<?php

declare(strict_types=1);

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Tomasz Gemza
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class AntiXss extends Constraint
{
    public const XSS_FOUND_ERROR = 'fce1015f-95ed-44e4-b96e-f033c9ff67e3';

    protected static $errorNames = [
        self::XSS_FOUND_ERROR => 'XSS_FOUND_ERROR',
    ];

    public string $message = 'XSS attack detected';

    public function __construct(
        ?string $message = null,
        mixed $options = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        $this->message = $message ?? $this->message;

        parent::__construct($options, $groups, $payload);
    }
}
