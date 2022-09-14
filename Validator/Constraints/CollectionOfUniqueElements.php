<?php

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class CollectionOfUniqueElements.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Tomasz Gemza
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class CollectionOfUniqueElements extends Constraint
{
    public const FOUND_DUPLICATES_ERROR = '93ae5784-4ec7-4d3d-bde6-b231c1e2802e';
    public const INVALID_COLLECTION_ERROR = 'ccde91a5-a73f-42ad-9c94-320ecc2cd381';

    protected static $errorNames = [
        self::FOUND_DUPLICATES_ERROR => 'FOUND_DUPLICATES_ERROR',
        self::INVALID_COLLECTION_ERROR => 'INVALID_COLLECTION_ERROR',
    ];

    /**
     * @var bool
     */
    public $matchCase = false;

    /**
     * @var callable|null
     */
    public $customNormalizationFunction = null;

    /**
     * @var string
     */
    public $message = 'The collection contains duplicated elements ({{ duplicates }})';

    /**
     * @var string
     */
    public $invalidCollectionMessage = 'The value is not a valid collection';
}
