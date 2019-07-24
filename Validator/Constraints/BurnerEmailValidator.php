<?php

namespace SecIT\ValidationBundle\Validator\Constraints;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class BurnerEmailValidator.
 *
 * @author Tomasz Gemza
 */
class BurnerEmailValidator extends ConstraintValidator
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * BurnerEmailValidator constructor.
     *
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof BurnerEmail) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\BurnerEmail');
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
            return;
        }

        $isBurnerEmail = in_array($domain, $burnerDomains, true);

        if ($isBurnerEmail) {
            $this->context->buildViolation($constraint->message)
                ->setCode(BurnerEmail::BURNER_EMAIL_ERROR)
                ->addViolation();
        }
    }

    /**
     * Extract domain from an email address.
     *
     * @param string $email
     *
     * @return string|null
     */
    private function getEmailDomain(string $email): ?string
    {
        if (strpos($email, '@') === false) {
            return null;
        }

        return trim(substr(strrchr($email, '@'), 1));
    }

    /**
     * Get burner email providers domains.
     *
     * @return array
     */
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
