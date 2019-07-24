<?php

namespace SecIT\ValidationBundle\Tests\Validator\Constraints;

use PHPUnit\Framework\TestCase;
use SecIT\ValidationBundle\Validator\Constraints\BurnerEmail;
use SecIT\ValidationBundle\Validator\Constraints\BurnerEmailValidator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

/**
 * Class BurnerEmailValidatorTest.
 *
 * @author Tomasz Gemza
 */
class BurnerEmailValidatorTest extends TestCase
{
    /**
     * Test valid values.
     *
     * @param mixed $values
     *
     * @dataProvider getValidValues
     */
    public function testValidValues($values): void
    {
        $constraint = new BurnerEmail();

        $validator = $this->configureValidator();
        $validator->validate($values, $constraint);
    }

    /**
     * Test invalid values.
     *
     * @param mixed $values
     *
     * @dataProvider getInvalidValues
     */
    public function testInvalidValues($values): void
    {
        $constraint = new BurnerEmail();

        $validator = $this->configureValidator($constraint->message);
        $validator->validate($values, $constraint);
    }

    /**
     * Invalid values.
     *
     * @return array
     */
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

    /**
     * Valid values.
     *
     * @return array
     */
    public function getInvalidValues(): array
    {
        return [
            ['qwdqwdasd@10minutesmail.com'],
            ['test@0mel.com'],
            ['qwe@10mail.org   '],
        ];
    }

    /**
     * Configure validator.
     *
     * @param null $expectedMessage
     *
     * @return BurnerEmailValidator
     */
    private function configureValidator($expectedMessage = null): BurnerEmailValidator
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
