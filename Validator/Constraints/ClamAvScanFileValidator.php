<?php

declare(strict_types=1);

namespace SecIT\ValidationBundle\Validator\Constraints;

use Sineflow\ClamAV\Scanner;
use Symfony\Component\HttpFoundation\File\File as HttpFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Tomasz Gemza
 */
class ClamAvScanFileValidator extends ConstraintValidator
{
    public function __construct(
        private readonly Scanner $scanner,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ClamAvScanFile) {
            throw new UnexpectedTypeException($constraint, ClamAvScanFile::class);
        }

        if (!$value instanceof HttpFile) {
            return;
        }

        try {
            $scannedFile = $this->scanner->scan($value->getPathname());
            if (!$scannedFile->isClean()) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ infectionName }}', $scannedFile->getVirusName())
                    ->setCode(ClamAvScanFile::FILE_INFECTED_ERROR)
                    ->addViolation();
            }
        } catch (\Exception $exception) {
            if (!$constraint->ignoreScanErrors) {
                $this->context->buildViolation($constraint->scanFailedMessage)
                    ->setParameter('{{ message }}', $exception->getMessage())
                    ->setCode(ClamAvScanFile::SCAN_FAILED_ERROR)
                    ->addViolation();
            }
        }
    }
}
