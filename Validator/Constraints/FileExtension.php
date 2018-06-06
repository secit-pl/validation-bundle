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
class FileExtension extends Constraint
{
    const INVALID_EXTENSION_ERROR = '11edd7eb-5872-4b6e-9f12-89923999fd0e';

    protected static $errorNames = [
        self::INVALID_EXTENSION_ERROR => 'INVALID_EXTENSION_ERROR',
    ];

    /**
     * @var array
     */
    public $validExtensions = [];

    /**
     * @var bool
     */
    public $matchCase = false;

    /**
     * @var string
     */
    public $message = 'The extension of the file is invalid ({{ extension }}). Allowed extensions are {{ extensions }}.';

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return 'validExtensions';
    }
}

