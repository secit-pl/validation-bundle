<?php

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class BurnerEmail.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Tomasz Gemza
 */
class BurnerEmail extends Constraint
{
    public const BURNER_EMAIL_ERROR = '0697c190-0374-4050-80ba-588ea0ecdf77';

    protected static $errorNames = [
        self::BURNER_EMAIL_ERROR => 'BURNER_EMAIL_ERROR',
    ];

    /**
     * @var string
     */
    public $message = 'Throw away email addresses (burner emails) are not allowed';
}

