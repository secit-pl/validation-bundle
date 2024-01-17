<?php

declare(strict_types=1);

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Tomasz Gemza
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BurnerEmail extends Constraint
{
    public const BURNER_EMAIL_ERROR = '0697c190-0374-4050-80ba-588ea0ecdf77';
    public const RELATED_PACKAGE_NOT_INSTALLED_ERROR = '4323b8ab-b0e1-4a91-a1f2-790a1d4d8907';

    protected static array $errorNames = [
        self::BURNER_EMAIL_ERROR => 'BURNER_EMAIL_ERROR',
        self::RELATED_PACKAGE_NOT_INSTALLED_ERROR => 'RELATED_PACKAGE_NOT_INSTALLED_ERROR',
    ];

    public function __construct(
        public string $message = 'Throw away email addresses (burner emails) are not allowed',
        public string $relatedPackageNotInstalledMessage = 'You need to install the wesbos/burner-email-providers package to use the BurnerEmailValidator',
        mixed $options = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options, $groups, $payload);
    }
}

