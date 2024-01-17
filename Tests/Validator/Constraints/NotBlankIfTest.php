<?php

namespace SecIT\ValidationBundle\Tests\Validator\Constraints;

use PHPUnit\Framework\TestCase;
use SecIT\ValidationBundle\Validator\Constraints\NotBlankIf;
use SecIT\ValidationBundle\Validator\Constraints\NotBlankIfValidator;
use Symfony\Component\DependencyInjection\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\Expression as ExpressionObject;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

/**
 * @author Tomasz Gemza
 */
class NotBlankIfTest extends TestCase
{
    private ?\stdClass $testObject = null;

    protected function setUp(): void
    {
        $this->testObject = new \stdClass();
        $this->testObject->boolValue = true;
        $this->testObject->textValue = 'test';
    }

    /**
     * @dataProvider getValidValues
     */
    public function testValidValue(string|ExpressionObject|null $condition, mixed $value): void
    {
        $constraint = new NotBlankIf($condition);

        $validator = $this->configureValidator();
        $validator->validate($value, $constraint);
    }

    /**
     * @dataProvider getInvalidValues
     */
    public function testInvalidValue(string|ExpressionObject|null $condition, mixed $value): void
    {
        $constraint = new NotBlankIf($condition);

        $validator = $this->configureValidator($constraint->message);
        $validator->validate($value, $constraint);
    }

    public static function getValidValues(): array
    {
        return [
            ['this.boolValue', 'test'],
            ['this.boolValue or this.textValue == "test"', '1'],
            ['!this.boolValue', 'test'],
            ['!this.boolValue', ''],
        ];
    }

    public static function getInvalidValues(): array
    {
        return [
            ['this.boolValue', ''],
            ['this.boolValue or this.textValue == "test"', ''],
            ['this.boolValue', null],
        ];
    }

    private function configureValidator(?string $expectedMessage = null): NotBlankIfValidator
    {
        $builder = $this->getMockBuilder(ConstraintViolationBuilder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['addViolation'])
            ->getMock();

        $context = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['buildViolation'])
            ->onlyMethods(['getObject'])
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
