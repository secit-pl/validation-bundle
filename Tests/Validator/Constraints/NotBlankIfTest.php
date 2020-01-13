<?php

namespace SecIT\ValidationBundle\Tests\Validator\Constraints;

use PHPUnit\Framework\TestCase;
use SecIT\ValidationBundle\Validator\Constraints\NotBlankIf;
use SecIT\ValidationBundle\Validator\Constraints\NotBlankIfValidator;
use Symfony\Component\DependencyInjection\ExpressionLanguage;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

/**
 * Class NotBlankIfTest.
 *
 * @author Tomasz Gemza
 */
class NotBlankIfTest extends TestCase
{
    /**
     * @var null|\stdClass
     */
    private $testObject = null;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->testObject = new \stdClass();
        $this->testObject->boolValue = true;
        $this->testObject->textValue = 'test';
    }

    /**
     * Test valid values.
     *
     * @param mixed $condition
     * @param mixed $value
     *
     * @dataProvider getValidValues
     */
    public function testValidValue($condition, $value): void
    {
        $constraint = new NotBlankIf(['expression' => $condition]);

        $validator = $this->configureValidator();
        $validator->validate($value, $constraint);
    }

    /**
     * Test invalid values.
     *
     * @param mixed $condition
     * @param mixed $value
     *
     * @dataProvider getInvalidValues
     */
    public function testInvalidValue($condition, $value): void
    {
        $constraint = new NotBlankIf(['expression' => $condition]);

        $validator = $this->configureValidator($constraint->message);
        $validator->validate($value, $constraint);
    }

    /**
     * Invalid values.
     *
     * @return array
     */
    public function getValidValues(): array
    {
        return [
            ['this.boolValue', 'test'],
            ['this.boolValue or this.textValue == "test"', '1'],
            ['!this.boolValue', 'test'],
            ['!this.boolValue', ''],
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
            ['this.boolValue', ''],
            ['this.boolValue or this.textValue == "test"', ''],
            ['this.boolValue', null],
        ];
    }

    /**
     * Configure validator.
     *
     * @param null $expectedMessage
     *
     * @return NotBlankIfValidator
     */
    private function configureValidator($expectedMessage = null): NotBlankIfValidator
    {
        $builder = $this->getMockBuilder(ConstraintViolationBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['addViolation'])
            ->getMock();

        $context = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->setMethods(['buildViolation'])
            ->setMethods(['getObject'])
            ->getMock();

        $context->expects($this->any())
            ->method('getObject')
            ->will($this->returnValue($this->testObject));

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

        $validator = new NotBlankIfValidator(new ExpressionLanguage());
        $validator->initialize($context);

        return $validator;
    }
}
