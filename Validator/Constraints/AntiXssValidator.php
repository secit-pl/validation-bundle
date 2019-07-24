<?php

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use voku\helper\AntiXSS as VokuAntiXss;

/**
 * Class AntiXssValidator.
 *
 * @author Tomasz Gemza
 */
class AntiXssValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof AntiXss) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\AntiXss');
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
