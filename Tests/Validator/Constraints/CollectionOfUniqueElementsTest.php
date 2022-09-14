<?php

namespace SecIT\ValidationBundle\Tests\Validator\Constraints;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use SecIT\ValidationBundle\Validator\Constraints\CollectionOfUniqueElements;
use SecIT\ValidationBundle\Validator\Constraints\CollectionOfUniqueElementsValidator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

/**
 * @author Tomasz Gemza
 */
class CollectionOfUniqueElementsTest extends TestCase
{
    /**
     * @dataProvider getValidValues
     */
    public function testValidValues(mixed $values, ?bool $matchCase = false): void
    {
        $validator = $this->configureValidator();
        $validator->validate($values, new CollectionOfUniqueElements($matchCase));
    }

    /**
     * @dataProvider getInvalidCollections
     */
    public function testInvalidCollections(mixed $values): void
    {
        $constraint = new CollectionOfUniqueElements();

        $validator = $this->configureValidator($constraint->invalidCollectionMessage);
        $validator->validate($values, $constraint);
    }

    /**
     * @dataProvider getInvalidValues
     */
    public function testInvalidValues(mixed $values, ?bool $matchCase = false): void
    {
        $constraint = new CollectionOfUniqueElements($matchCase);

        $validator = $this->configureValidator($constraint->message);
        $validator->validate($values, $constraint);
    }

    public function getValidValues(): array
    {
        return [
            [[]],
            [['aaa', 'bbb', 'ccc']],
            [['aaa', 'bbb', 'AAA'], true],
            [new ArrayCollection()],
            [new ArrayCollection(['aaa', 'bbb', 'ccc'])],
            [new ArrayCollection(['aaa', 'bbb', 'AAA']), true],
            [[
                new UploadedFile(__FILE__, basename(__FILE__), null, null, true),
                new UploadedFile(dirname(dirname(__DIR__)).'/bootstrap.php', 'bootstrap.php', null, null, true),
            ]],
        ];
    }

    public function getInvalidCollections(): array
    {
        return [
            [''],
            ['aaa'],
            [111],
            [null],
            [new UploadedFile(__FILE__, basename(__FILE__), null, null, true)],
        ];
    }

    public function getInvalidValues(): array
    {
        return [
            [['aaa', 'bbb', 'aaa']],
            [['aaa', 'AAA', 'AAA'], true],
            [new ArrayCollection(['aaa', 'bbb', 'aaa'])],
            [new ArrayCollection(['aaa', 'AAA', 'AAA']), true],
            [[
                new UploadedFile(__FILE__, basename(__FILE__), null, null, true),
                new UploadedFile(__FILE__, basename(__FILE__), null, null, true),
            ]],
        ];
    }

    private function configureValidator(?string $expectedMessage = null): CollectionOfUniqueElementsValidator
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
