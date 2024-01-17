<?php

declare(strict_types=1);

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Tomasz Gemza
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class CollectionOfUniqueElements extends Constraint
{
    public const FOUND_DUPLICATES_ERROR = '93ae5784-4ec7-4d3d-bde6-b231c1e2802e';
    public const INVALID_COLLECTION_ERROR = 'ccde91a5-a73f-42ad-9c94-320ecc2cd381';

    protected static array $errorNames = [
        self::FOUND_DUPLICATES_ERROR => 'FOUND_DUPLICATES_ERROR',
        self::INVALID_COLLECTION_ERROR => 'INVALID_COLLECTION_ERROR',
    ];

    public function __construct(
        public bool $matchCase = false,
        public mixed $customNormalizationFunction = null,
        public string $message = 'The collection contains duplicated elements ({{ duplicates }})',
        public string $invalidCollectionMessage = 'The value is not a valid collection',
        mixed $options = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
