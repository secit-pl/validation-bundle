<?php

declare(strict_types=1);

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\ExpressionLanguage\Expression as ExpressionObject;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Exception\LogicException;

/**
 * @author Tomasz Gemza
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class NotBlankIf extends NotBlank
{
    public const IS_BLANK_IF_ERROR = '199bb09f-0732-42f4-8bcf-f01ff89526c0';

    protected static array $errorNames = [
        self::IS_BLANK_IF_ERROR => 'IS_BLANK_IF_ERROR',
    ];

    public function __construct(
        public string|ExpressionObject|null $expression = null,
        public array $values = [],
        ?string $message = null,
        bool $allowNull = null,
        callable $normalizer = null,
        array $groups = null,
        mixed $payload = null,
        array $options = null,
    ) {
        if (!class_exists(ExpressionLanguage::class)) {
            throw new LogicException(sprintf('The "symfony/expression-language" component is required to use the "%s" constraint.', __CLASS__));
        }

        $options['expression'] = $expression ?? $options['expression'];
        $options['values'] = $values ?? $options['values'];

        parent::__construct($options, $message, $allowNull, $normalizer, $groups, $payload);
    }

    public function getDefaultOption(): string
    {
        return 'expression';
    }

    public function getRequiredOptions(): array
    {
        return ['expression'];
    }
}
