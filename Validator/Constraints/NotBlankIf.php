<?php

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Exception\LogicException;

/**
 * Class NotBlankIf.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Tomasz Gemza
 */
class NotBlankIf extends NotBlank
{
    public const IS_BLANK_IF_ERROR = '199bb09f-0732-42f4-8bcf-f01ff89526c0';

    protected static $errorNames = [
        self::IS_BLANK_IF_ERROR => 'IS_BLANK_IF_ERROR',
    ];

    public $expression;
    public $values = [];

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (!class_exists(ExpressionLanguage::class)) {
            throw new LogicException(sprintf('The "symfony/expression-language" component is required to use the "%s" constraint.', __CLASS__));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return 'expression';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions()
    {
        return ['expression'];
    }
}
