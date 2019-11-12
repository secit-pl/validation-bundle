<?php

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\HttpFoundation\File\File as FileObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class FileExtensionValidator.
 *
 * @author Tomasz Gemza
 */
class FileExtensionValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof FileExtension) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\FileExtension');
        }

        if (null === $value || '' === $value) {
            return;
        }

        $extension = null;
        if ($value instanceof UploadedFile && $value->isValid()) {
            $extension = $value->getClientOriginalExtension();
        } elseif (!is_scalar($value) && !$value instanceof FileObject && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        } elseif ($value instanceof FileObject) {
            $extension = $value->getExtension();
        } else {
            $extension = pathinfo((string) $value, PATHINFO_EXTENSION);
        }

        $validExtensions = $constraint->validExtensions;
        $disallowedExtensions = $constraint->disallowedExtensions;
        if (!$constraint->matchCase) {
            $extension = strtolower($extension);
            $validExtensions = array_map('strtolower', $validExtensions);
            $disallowedExtensions = array_map('strtolower', $disallowedExtensions);
        }

        if ($validExtensions && !in_array($extension, $validExtensions)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ extension }}', $this->formatValue($extension))
                ->setParameter('{{ extensions }}', $this->formatValues($constraint->validExtensions))
                ->setCode(FileExtension::INVALID_EXTENSION_ERROR)
                ->addViolation();
        }

        if ($disallowedExtensions && in_array($extension, $disallowedExtensions)) {
            $this->context->buildViolation($constraint->disallowedMessage)
                ->setParameter('{{ extension }}', $this->formatValue($extension))
                ->setParameter('{{ extensions }}', $this->formatValues($constraint->disallowedExtensions))
                ->setCode(FileExtension::DISALLOWED_EXTENSION_ERROR)
                ->addViolation();
        }
    }
}
