<?php

namespace SecIT\ValidationBundle\Tests\Validator\Constraints;

use PHPUnit\Framework\TestCase;
use SecIT\ValidationBundle\Validator\Constraints\NaiveNoHtml;
use SecIT\ValidationBundle\Validator\Constraints\NaiveNoHtmlValidator;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

/**
 * @author Tomasz Gemza
 */
class NaiveNoHtmlValidatorTest extends TestCase
{
    /**
     * @dataProvider getStrongValidationValidValues
     */
    public function testStrongValidationValidValues($values): void
    {
        $constraint = new NaiveNoHtml();
        $constraint->strongValidation = true;

        $validator = $this->configureValidator();
        $validator->validate($values, $constraint);
    }

    /**
     * @dataProvider getStrongValidationInvalidValues
     */
    public function testStrongValidationInvalidValues(mixed $values): void
    {
        $constraint = new NaiveNoHtml();
        $constraint->strongValidation = true;

        $validator = $this->configureValidator($constraint->message);
        $validator->validate($values, $constraint);
    }

    /**
     * @dataProvider getWeakValidationValidValues
     */
    public function testWeakValidationValidValues(mixed $values): void
    {
        $constraint = new NaiveNoHtml();
        $constraint->strongValidation = false;

        $validator = $this->configureValidator();
        $validator->validate($values, $constraint);
    }

    /**
     * @dataProvider getWeakValidationInvalidValues
     */
    public function testWeakValidationInvalidValues(mixed $values): void
    {
        $constraint = new NaiveNoHtml();
        $constraint->strongValidation = false;

        $validator = $this->configureValidator($constraint->message);
        $validator->validate($values, $constraint);
    }

    public function getStrongValidationValidValues(): array
    {
        return [
            [null],
            [''],
            ['text'],
            ['text 1, 2, 3...'],
            ['&nbsp;'],
        ];
    }

    public function getStrongValidationInvalidValues(): array
    {
        return [
            ['<b>asd</b>'],
            ['<img src="http://example.com/test.png" alt="image">asd</img>'],
            ['<br />'],
            ['text with unclosed html <tag'],
            ['<'],
            ['I <3 You'],
        ];
    }

    public function getWeakValidationValidValues(): array
    {
        return [
            [null],
            [''],
            ['text'],
            ['text 1, 2, 3...'],
            ['&nbsp;'],
            ['text with unclosed html <tag'],
            ['<'],
            ['I <3 You'],
        ];
    }

    public function getWeakValidationInvalidValues(): array
    {
        return [
            ['<b>asd</b>'],
            ['<img src="http://example.com/test.png" alt="image">asd</img>'],
            ['<br />'],
        ];
    }

    private function configureValidator(?string $expectedMessage = null): NaiveNoHtmlValidator
    {
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

        $validator = new NaiveNoHtmlValidator();
        $validator->initialize($context);

        return $validator;
    }
}
