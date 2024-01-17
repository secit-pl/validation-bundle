<?php

declare(strict_types=1);

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Tomasz Gemza
 */
class BurnerEmailValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof BurnerEmail) {
            throw new UnexpectedTypeException($constraint, BurnerEmail::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $domain = $this->getEmailDomain($value);
        if (false === $domain) {
            return;
        }

        $burnerDomains = $this->getBurnerEmailDomains();
        if (!$burnerDomains) {
            $this->context->buildViolation($constraint->relatedPackageNotInstalledMessage)
                ->setCode(BurnerEmail::RELATED_PACKAGE_NOT_INSTALLED_ERROR)
                ->addViolation();

            return;
        }

        $isBurnerEmail = in_array($domain, $burnerDomains, true);

        if ($isBurnerEmail) {
            $this->context->buildViolation($constraint->message)
                ->setCode(BurnerEmail::BURNER_EMAIL_ERROR)
                ->addViolation();
        }
    }

    private function getEmailDomain(string $email): ?string
    {
        if (!str_contains($email, '@')) {
            return null;
        }

        return trim(substr(strrchr($email, '@'), 1));
    }

    private function getBurnerEmailDomains(): array
    {
        $vendorPath = $this->parameterBag->get('kernel.project_dir').DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR;

        $domainsListFilePath = $vendorPath.'wesbos'.DIRECTORY_SEPARATOR.'burner-email-providers'.DIRECTORY_SEPARATOR.'emails.txt';
        if (!file_exists($domainsListFilePath) || !is_readable($domainsListFilePath)) {
            return [];
        }

        return array_map('trim', file($domainsListFilePath));
    }
}
