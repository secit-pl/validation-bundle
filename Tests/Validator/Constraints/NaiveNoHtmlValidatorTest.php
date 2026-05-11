<?php

namespace SecIT\ValidationBundle\Tests\Validator\Constraints;

use PHPUnit\Framework\Attributes\DataProvider;
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
    #[DataProvider('getStrongValidationValidValues')]
    public function testStrongValidationValidValues($values): void
    {
        $constraint = new NaiveNoHtml();
        $constraint->strongValidation = true;

        $validator = $this->configureValidator();
        $validator->validate($values, $constraint);
    }

    #[DataProvider('getStrongValidationInvalidValues')]
    public function testStrongValidationInvalidValues(mixed $values): void
    {
        $constraint = new NaiveNoHtml();
        $constraint->strongValidation = true;

        $validator = $this->configureValidator($constraint->message);
        $validator->validate($values, $constraint);
    }

    #[DataProvider('getWeakValidationValidValues')]
    public function testWeakValidationValidValues(mixed $values): void
    {
        $constraint = new NaiveNoHtml();
        $constraint->strongValidation = false;

        $validator = $this->configureValidator();
        $validator->validate($values, $constraint);
    }

    #[DataProvider('getWeakValidationInvalidValues')]
    public function testWeakValidationInvalidValues(mixed $values): void
    {
        $constraint = new NaiveNoHtml();
        $constraint->strongValidation = false;

        $validator = $this->configureValidator($constraint->message);
        $validator->validate($values, $constraint);
    }

    public static function getStrongValidationValidValues(): array
    {
        return [
            [null],
            [''],
            ['text'],
            ['text 1, 2, 3...'],
            ['&nbsp;'],
        ];
    }

    public static function getStrongValidationInvalidValues(): array
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

    public static function getWeakValidationValidValues(): array
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

    public static function getWeakValidationInvalidValues(): array
    {
        return [
            ['<b>asd</b>'],
            ['<img src="http://example.com/test.png" alt="image">asd</img>'],
            ['<br />'],
        ];
    }

    private function configureValidator(?string $expectedMessage = null): NaiveNoHtmlValidator
    {
        $context = $this->createMock(ExecutionContext::class);

        if ($expectedMessage) {
            $builder = $this->createPartialMock(
                ConstraintViolationBuilder::class,
                ['addViolation']
            );

            $builder->expects($this->once())
                ->method('addViolation');

            $context->expects($this->once())
                ->method('buildViolation')
                ->with($this->equalTo($expectedMessage))
                ->willReturn($builder);
        } else {
            $context->expects($this->never())
                ->method('buildViolation');
        }

        $validator = new NaiveNoHtmlValidator();
        $validator->initialize($context);

        return $validator;
    }
}
