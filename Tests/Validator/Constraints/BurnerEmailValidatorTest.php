<?php

namespace SecIT\ValidationBundle\Tests\Validator\Constraints;

use PHPUnit\Framework\TestCase;
use SecIT\ValidationBundle\Validator\Constraints\BurnerEmail;
use SecIT\ValidationBundle\Validator\Constraints\BurnerEmailValidator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

/**
 * @author Tomasz Gemza
 */
class BurnerEmailValidatorTest extends TestCase
{
    /**
     * @dataProvider getValidValues
     */
    public function testValidValues(mixed $values): void
    {
        $validator = $this->configureValidator();
        $validator->validate($values, new BurnerEmail());
    }

    /**
     * @dataProvider getInvalidValues
     */
    public function testInvalidValues(mixed $values): void
    {
        $constraint = new BurnerEmail();

        $validator = $this->configureValidator($constraint->message);
        $validator->validate($values, $constraint);
    }

    public function getValidValues(): array
    {
        return [
            [null],
            [''],
            ['test@gmail.com'],
            ['mail@microsoft.com'],
            ['test@apple.com   '],
        ];
    }

    public function getInvalidValues(): array
    {
        return [
            ['qwdqwdasd@10minutesmail.com'],
            ['test@0mel.com'],
            ['qwe@10mail.org   '],
        ];
    }

    private function configureValidator(?string $expectedMessage = null): BurnerEmailValidator
    {
        $parameterBag = new ParameterBag();
        $parameterBag->set('kernel.project_dir', dirname(__DIR__, 3));

        $builder = $this->getMockBuilder(ConstraintViolationBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['addViolation'])
            ->getMock();

        $context = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->setMethods(['buildViolation'])
            ->getMock();

        if ($expectedMessage) {
            $builder->expects($this->once())
                ->method('addViolation');

            $context->expects($this->once())
                ->method('buildViolation')
                ->with($this->equalTo($expectedMessage))
                ->will($this->returnValue($builder));
        } else {
            $context->expects($this->never())
                ->method('buildViolation');
        }

        $validator = new BurnerEmailValidator($parameterBag);
        $validator->initialize($context);

        return $validator;
    }
}
