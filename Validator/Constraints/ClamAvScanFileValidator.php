<?php

declare(strict_types=1);

namespace SecIT\ValidationBundle\Validator\Constraints;

use Sineflow\ClamAV\Scanner;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File as HttpFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Tomasz Gemza
 */
class ClamAvScanFileValidator extends ConstraintValidator
{
    private ?Scanner $scanner;

    public function __construct(ContainerInterface $container)
    {
        $this->scanner = $container->get('sineflow.clamav.scanner', ContainerInterface::NULL_ON_INVALID_REFERENCE);
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ClamAvScanFile) {
            throw new UnexpectedTypeException($constraint, ClamAvScanFile::class);
        }

        if (null === $this->scanner || !$value instanceof HttpFile) {
            return;
        }

        $filePath = $value->getPathname();
        $baseFilePermissions = fileperms($filePath);

        try {
            chmod($filePath, $baseFilePermissions | 0644);

            $scannedFile = $this->scanner->scan($filePath);
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
        } finally {
            chmod($filePath, $baseFilePermissions);
        }
    }
}
