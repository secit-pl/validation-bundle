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
class BurnerEmail extends Constraint
{
    public const BURNER_EMAIL_ERROR = '0697c190-0374-4050-80ba-588ea0ecdf77';
    public const RELATED_PACKAGE_NOT_INSTALLED_ERROR = '4323b8ab-b0e1-4a91-a1f2-790a1d4d8907';

    protected static $errorNames = [
        self::BURNER_EMAIL_ERROR => 'BURNER_EMAIL_ERROR',
        self::RELATED_PACKAGE_NOT_INSTALLED_ERROR => 'RELATED_PACKAGE_NOT_INSTALLED_ERROR',
    ];

    public string $message = 'Throw away email addresses (burner emails) are not allowed';
    public string $relatedPackageNotInstalledMessage = 'You need to install the wesbos/burner-email-providers package to use the BurnerEmailValidator';

    public function __construct(
        ?string $message = null,
        ?string $relatedPackageNotInstalledMessage = null,
        mixed $options = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        $this->message = $message ?? $this->message;
        $this->relatedPackageNotInstalledMessage = $relatedPackageNotInstalledMessage ?? $this->relatedPackageNotInstalledMessage;

        parent::__construct($options, $groups, $payload);
    }
}

