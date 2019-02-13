<?php

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class NaiveNoHtmlValidator.
 *
 * @author Tomasz Gemza
 */
class NaiveNoHtmlValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof NaiveNoHtml) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\NaiveNoHtml');
        }

        if (null === $value || '' === $value) {
            return;
        }

        $probablyHtml = strpos($value, '<') !== false;

        if ($probablyHtml) {
            $this->context->buildViolation($constraint->message)
                ->setCode(NaiveNoHtml::HTML_FOUND_ERROR)
                ->addViolation();
        }
    }
}

