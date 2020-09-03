<?php

namespace SecIT\ValidationBundle\Tests\Validator\Constraints;

use PHPUnit\Framework\TestCase;
use SecIT\ValidationBundle\Validator\Constraints\NaiveNoHtml;
use SecIT\ValidationBundle\Validator\Constraints\NaiveNoHtmlValidator;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

/**
 * Class NaiveNoHtmlValidatorTest.
 *
 * @author Tomasz Gemza
 */
class NaiveNoHtmlValidatorTest extends TestCase
{
    /**
     * Test strong validation valid values.
     *
     * @param mixed $values
     *
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
     * Test strong validation invalid values.
     *
     * @param mixed $values
     *
     * @dataProvider getStrongValidationInvalidValues
     */
    public function testStrongValidationInvalidValues($values): void
    {
        $constraint = new NaiveNoHtml();
        $constraint->strongValidation = true;

        $validator = $this->configureValidator($constraint->message);
        $validator->validate($values, $constraint);
    }

    /**
     * Test weak validation valid values.
     *
     * @param mixed $values
     *
     * @dataProvider getWeakValidationValidValues
     */
    public function testWeakValidationValidValues($values): void
    {
        $constraint = new NaiveNoHtml();
        $constraint->strongValidation = false;

        $validator = $this->configureValidator();
        $validator->validate($values, $constraint);
    }

    /**
     * Test weak validation invalid values.
     *
     * @param mixed $values
     *
     * @dataProvider getWeakValidationInvalidValues
     */
    public function testWeakValidationInvalidValues($values): void
    {
        $constraint = new NaiveNoHtml();
        $constraint->strongValidation = false;

        $validator = $this->configureValidator($constraint->message);
        $validator->validate($values, $constraint);
    }

    /**
     * Strong validation valid values.
     *
     * @return array
     */
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

    /**
     * Strong validation invalid values.
     *
     * @return array
     */
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

    /**
     * Weak validation valid values.
     *
     * @return array
     */
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

    /**
     * Weak validation invalid values.
     *
     * @return array
     */
    public function getWeakValidationInvalidValues(): array
    {
        return [
            ['<b>asd</b>'],
            ['<img src="http://example.com/test.png" alt="image">asd</img>'],
            ['<br />'],
        ];
    }

    /**
     * Configure validator.
     *
     * @param null $expectedMessage
     *
     * @return NaiveNoHtmlValidator
     */
    private function configureValidator($expectedMessage = null): NaiveNoHtmlValidator
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
