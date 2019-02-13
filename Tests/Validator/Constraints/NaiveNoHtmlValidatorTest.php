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
     * Test valid values.
     *
     * @param mixed $values
     *
     * @dataProvider getValidValues
     */
    public function testValidValues($values): void
    {
        $constraint = new NaiveNoHtml();

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
        $constraint = new NaiveNoHtml();

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
            ['text'],
            ['text 1, 2, 3...'],
            ['&nbsp;'],
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
            ['<b>asd</b>'],
            ['<img src="http://example.com/test.png" alt="image">asd</img>'],
            ['text with unclosed html <tag'],
            ['<'],
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
