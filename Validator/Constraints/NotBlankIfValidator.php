<?php

declare(strict_types=1);

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\DependencyInjection\ExpressionLanguage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlankValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Tomasz Gemza
 */
class NotBlankIfValidator extends NotBlankValidator
{
    private ExpressionLanguage|null $expressionLanguage;

    public function __construct(ExpressionLanguage $expressionLanguage = null)
    {
        $this->expressionLanguage = $expressionLanguage;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof NotBlankIf) {
            throw new UnexpectedTypeException($constraint, NotBlankIf::class);
        }

        $variables = $constraint->values;
        $variables['value'] = $value;
        $variables['this'] = $this->context->getObject();

        if ($this->getExpressionLanguage()->evaluate($constraint->expression, $variables)) {
            parent::validate($value, $constraint);
        }
    }

    private function getExpressionLanguage(): ExpressionLanguage
    {
        if (null === $this->expressionLanguage) {
            $this->expressionLanguage = new ExpressionLanguage();
        }

        return $this->expressionLanguage;
    }
}
