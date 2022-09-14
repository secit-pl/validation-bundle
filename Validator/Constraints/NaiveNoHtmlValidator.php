<?php

declare(strict_types=1);

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Tomasz Gemza
 */
class NaiveNoHtmlValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof NaiveNoHtml) {
            throw new UnexpectedTypeException($constraint, NaiveNoHtml::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if ($constraint->strongValidation) {
            $probablyHtml = false !== strpos($value, '<');
        } else {
            $probablyHtml = preg_match('/<[^<]+>/msU', $value);
        }

        if ($probablyHtml) {
            $this->context->buildViolation($constraint->message)
                ->setCode(NaiveNoHtml::HTML_FOUND_ERROR)
                ->addViolation();
        }
    }
}
