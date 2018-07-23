<?php

namespace SecIT\ValidationBundle\Tests\Validator\Constraints;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use SecIT\ValidationBundle\Validator\Constraints\CollectionOfUniqueElements;
use SecIT\ValidationBundle\Validator\Constraints\CollectionOfUniqueElementsValidator;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

/**
 * Class UniqueCollectionElementsTest.
 *
 * @author Tomasz Gemza
 */
class UniqueCollectionElementsTest extends TestCase
{
    /**
     * Test valid values.
     *
     * @param mixed $values
     * @param bool  $matchCase
     *
     * @dataProvider getValidValues
     */
    public function testValidValues($values, ?bool $matchCase = false): void
    {
        $constraint = new CollectionOfUniqueElements();
        $constraint->matchCase = $matchCase;

        $validator = $this->configureValidator();
        $validator->validate($values, $constraint);
    }

    /**
     * Test invalid collection.
     *
     * @param mixed $values
     *
     * @dataProvider getInvalidCollections
     */
    public function testInvalidCollections($values): void
    {
        $constraint = new CollectionOfUniqueElements();

        $validator = $this->configureValidator($constraint->invalidCollectionMessage);
        $validator->validate($values, $constraint);
    }

    /**
     * Test invalid values.
     *
     * @param mixed $values
     * @param bool  $matchCase
     *
     * @dataProvider getInvalidValues
     */
    public function testInvalidValues($values, ?bool $matchCase = false): void
    {
        $constraint = new CollectionOfUniqueElements();
        $constraint->matchCase = $matchCase;

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
            [[]],
            [['aaa', 'bbb', 'ccc']],
            [['aaa', 'bbb', 'AAA'], true],
            [new ArrayCollection()],
            [new ArrayCollection(['aaa', 'bbb', 'ccc'])],
            [new ArrayCollection(['aaa', 'bbb', 'AAA']), true],
        ];
    }

    /**
     * Valid values.
     *
     * @return array
     */
    public function getInvalidCollections(): array
    {
        return [
            [''],
            ['aaa'],
            [111],
            [null],
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
            [['aaa', 'bbb', 'aaa']],
            [['aaa', 'AAA', 'AAA'], true],
            [new ArrayCollection(['aaa', 'bbb', 'aaa'])],
            [new ArrayCollection(['aaa', 'AAA', 'AAA']), true],
        ];
    }

    /**
     * Configure validator.
     *
     * @param null $expectedMessage
     *
     * @return CollectionOfUniqueElementsValidator
     */
    private function configureValidator($expectedMessage = null): CollectionOfUniqueElementsValidator
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

        $validator = new CollectionOfUniqueElementsValidator();
        $validator->initialize($context);

        return $validator;
    }
}
