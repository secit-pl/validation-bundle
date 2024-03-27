<?php

declare(strict_types=1);

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Tomasz Gemza
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class ClamAvScanFile extends Constraint
{
    public const FILE_INFECTED_ERROR = '839e3eee-7d33-49c4-ae90-904d085d6ecb';
    public const SCAN_FAILED_ERROR = '5382556a-1835-4e45-8270-0d17cbf40d29';

    protected static array $errorNames = [
        self::FILE_INFECTED_ERROR => 'FILE_INFECTED_ERROR',
        self::SCAN_FAILED_ERROR => 'SCAN_FAILED_ERROR',
    ];

    public function __construct(
        public bool $ignoreScanErrors = false,
        public string $message = 'The file is infected with {{ infectionName }}.',
        public string $scanFailedMessage = 'Scan failed with message: {{ message }}.',
        mixed $options = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
