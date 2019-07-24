<?php

namespace SecIT\ValidationBundle\Validator\Constraints;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class CollectionOfUniqueElementsValidator.
 *
 * @author Tomasz Gemza
 */
class CollectionOfUniqueElementsValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CollectionOfUniqueElements) {
            throw new UnexpectedTypeException($constraint, CollectionOfUniqueElements::class);
        }

        if (!is_iterable($value)) {
            $this->context->buildViolation($constraint->invalidCollectionMessage)
                ->setCode(CollectionOfUniqueElements::INVALID_COLLECTION_ERROR)
                ->addViolation();
        }

        if ($value instanceof Collection) {
            $elements = $value->toArray();
        } else {
            $elements = (array) $value;
        }

        foreach ($elements as $key => $element) {
            if ($constraint->customNormalizationFunction) {
                $elements[$key] = call_user_func_array($constraint->customNormalizationFunction, [
                    $element,
                    $constraint->matchCase,
                ]);
            } else {
                $elements[$key] = $this->normalize($element, $constraint->matchCase);
            }
        }

        $counters = array_count_values($elements);
        $duplicates = array_filter($counters, function ($count) {
            return $count > 1;
        });

        if ($duplicates) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ duplicates }}', $this->formatValues(array_keys($duplicates)))
                ->setCode(CollectionOfUniqueElements::FOUND_DUPLICATES_ERROR)
                ->addViolation();
        }
    }

    /**
     * Normalize value to allow comparison.
     *
     * @param mixed $value
     * @param bool  $matchCase
     *
     * @return string
     */
    protected function normalize($value, bool $matchCase): string
    {
        if ($value instanceof UploadedFile) {
            if (UPLOAD_ERR_OK !== $value->getError()) {
                return '';
            }

            $filePath = $value->getPathname();
            if (!file_exists($filePath)) {
                return ''; // This should never happen
            }

            return md5_file($filePath);
        }

        $value = (string) $value;
        if (!$matchCase) {
            $value = mb_strtolower($value);
        }

        return $value;
    }
}
