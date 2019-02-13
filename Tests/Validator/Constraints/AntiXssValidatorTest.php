<?php

namespace SecIT\ValidationBundle\Tests\Validator\Constraints;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use SecIT\ValidationBundle\Validator\Constraints\AntiXss;
use SecIT\ValidationBundle\Validator\Constraints\AntiXssValidator;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

/**
 * Class AntiXssValidatorTest.
 *
 * @author Tomasz Gemza
 */
class AntiXssValidatorTest extends TestCase
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
        $constraint = new AntiXss();

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
        $constraint = new AntiXss();

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
            ['<b>asd</b>'],
            ['<img src="http://example.com/test.png" alt="image">asd</img>'],
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
            ['<script>alert(123);</script>'],
            ['<ScRipT>alert("XSS");</ScRipT>'],
            ['<script>alert(123)</script>'],
            ['<script>alert(�XSS�)</script> '],
            ['<script>alert(�XSS�);</script>'],
            ['<script>alert(�XSS�)</script>'],
            ['�><script>alert(�XSS�)</script>'],
            ['<script>alert(/XSS�)</script>'],
            ['<script>alert(/XSS/)</script>'],
            ['</script><script>alert(1)</script>'],
            ['�; alert(1);'],
            ['�)alert(1);//'],
            ['<audio src=1 onerror=alert(1)>'],
        ];
    }

    /**
     * Configure validator.
     *
     * @param null $expectedMessage
     *
     * @return AntiXssValidator
     */
    private function configureValidator($expectedMessage = null): AntiXssValidator
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

        $validator = new AntiXssValidator();
        $validator->initialize($context);

        return $validator;
    }
}
