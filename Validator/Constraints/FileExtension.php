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
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class FileExtension extends Constraint
{
    public const INVALID_EXTENSION_ERROR = '11edd7eb-5872-4b6e-9f12-89923999fd0e';
    public const DISALLOWED_EXTENSION_ERROR = 'a1d6781f-e656-4139-ac46-1f215ea5bbd4';

    protected static $errorNames = [
        self::INVALID_EXTENSION_ERROR => 'INVALID_EXTENSION_ERROR',
        self::DISALLOWED_EXTENSION_ERROR => 'DISALLOWED_EXTENSION_ERROR',
    ];

    /**
     * @var array
     */
    public $validExtensions = [];

    /**
     * @var array
     */
    public $disallowedExtensions = [];

    /**
     * @var bool
     */
    public $matchCase = false;

    /**
     * @var string
     */
    public $message = 'The extension of the file is invalid ({{ extension }}). Allowed extensions are {{ extensions }}.';

    /**
     * @var string
     */
    public $disallowedMessage = 'The extension of the file is invalid ({{ extension }}). Disallowed extensions are {{ extensions }}.';

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return 'validExtensions';
    }
}
