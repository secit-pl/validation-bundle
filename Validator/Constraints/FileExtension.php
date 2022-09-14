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
class FileExtension extends Constraint
{
    public const INVALID_EXTENSION_ERROR = '11edd7eb-5872-4b6e-9f12-89923999fd0e';
    public const DISALLOWED_EXTENSION_ERROR = 'a1d6781f-e656-4139-ac46-1f215ea5bbd4';

    protected static $errorNames = [
        self::INVALID_EXTENSION_ERROR => 'INVALID_EXTENSION_ERROR',
        self::DISALLOWED_EXTENSION_ERROR => 'DISALLOWED_EXTENSION_ERROR',
    ];

    public array $validExtensions = [];
    public array $disallowedExtensions = [];
    public bool $matchCase = false;
    public string $message = 'The extension of the file is invalid ({{ extension }}). Allowed extensions are {{ extensions }}.';
    public string $disallowedMessage = 'The extension of the file is invalid ({{ extension }}). Disallowed extensions are {{ extensions }}.';

    public function __construct(
        ?array $validExtensions = null,
        ?array $disallowedExtensions = null,
        ?bool $matchCase = null,
        ?string $message = null,
        ?string $disallowedMessage = null,
        mixed $options = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        $this->validExtensions = $validExtensions ?? $this->validExtensions;
        $this->disallowedExtensions = $disallowedExtensions ?? $this->disallowedExtensions;
        $this->matchCase = $matchCase ?? $this->matchCase;
        $this->message = $message ?? $this->message;
        $this->disallowedMessage = $disallowedMessage ?? $this->disallowedMessage;

        parent::__construct($options, $groups, $payload);
    }

    public function getDefaultOption()
    {
        return 'validExtensions';
    }
}
