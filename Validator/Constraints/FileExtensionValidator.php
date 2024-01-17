<?php

declare(strict_types=1);

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\HttpFoundation\File\File as FileObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Tomasz Gemza
 */
class FileExtensionValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FileExtension) {
            throw new UnexpectedTypeException($constraint, FileExtension::class);
        }

        if (!is_array($value)) {
            $value = [$value];
        }

        foreach ($value as $element) {
            $this->performSingleElementValidation($element, $constraint);
        }
    }

    private function performSingleElementValidation($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $extension = null;
        if ($value instanceof UploadedFile) {
            if ($value->isValid()) {
                $extension = $value->getClientOriginalExtension();
            } else {
                $extension = pathinfo($value->getClientOriginalName(), PATHINFO_EXTENSION);
            }
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

        if ($validExtensions && !in_array($extension, $validExtensions, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ extension }}', $this->formatValue($extension))
                ->setParameter('{{ extensions }}', $this->formatValues($validExtensions))
                ->setCode(FileExtension::INVALID_EXTENSION_ERROR)
                ->addViolation();
        }

        if ($disallowedExtensions && in_array($extension, $disallowedExtensions, true)) {
            $this->context->buildViolation($constraint->disallowedMessage)
                ->setParameter('{{ extension }}', $this->formatValue($extension))
                ->setParameter('{{ extensions }}', $this->formatValues($disallowedExtensions))
                ->setCode(FileExtension::DISALLOWED_EXTENSION_ERROR)
                ->addViolation();
        }
    }
}
