<?php

declare(strict_types=1);

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use voku\helper\AntiXSS as VokuAntiXss;

/**
 * @author Tomasz Gemza
 */
class AntiXssValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof AntiXss) {
            throw new UnexpectedTypeException($constraint, AntiXss::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $antiXss = new VokuAntiXss();
        $antiXss->xss_clean($value);

        if ($antiXss->isXssFound()) {
            $this->context->buildViolation($constraint->message)
                ->setCode(AntiXss::XSS_FOUND_ERROR)
                ->addViolation();
        }
    }
}
