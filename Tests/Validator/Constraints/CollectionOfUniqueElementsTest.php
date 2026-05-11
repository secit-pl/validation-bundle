<?php

namespace SecIT\ValidationBundle\Tests\Validator\Constraints;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Attributes\DataProvider;
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
    #[DataProvider('getValidValues')]
    public function testValidValues(mixed $values, ?bool $matchCase = false): void
    {
        $validator = $this->configureValidator();
        $validator->validate($values, new CollectionOfUniqueElements($matchCase));
    }

    #[DataProvider('getInvalidCollections')]
    public function testInvalidCollections(mixed $values): void
    {
        $constraint = new CollectionOfUniqueElements();

        $validator = $this->configureValidator($constraint->invalidCollectionMessage);
        $validator->validate($values, $constraint);
    }

    #[DataProvider('getInvalidValues')]
    public function testInvalidValues(mixed $values, ?bool $matchCase = false): void
    {
        $constraint = new CollectionOfUniqueElements($matchCase);

        $validator = $this->configureValidator($constraint->message);
        $validator->validate($values, $constraint);
    }

    public static function getValidValues(): array
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

    public static function getInvalidCollections(): array
    {
        return [
            [''],
            ['aaa'],
            [111],
            [null],
            [new UploadedFile(__FILE__, basename(__FILE__), null, null, true)],
        ];
    }

    public static function getInvalidValues(): array
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
        $context = $this->createMock(ExecutionContext::class);

        if ($expectedMessage) {
            $builder = $this->createPartialMock(
                ConstraintViolationBuilder::class,
                ['addViolation']
            );

            $builder->expects($this->atLeastOnce())
                ->method('addViolation');

            $context->expects($this->atLeastOnce())
                ->method('buildViolation')
                ->with($this->equalTo($expectedMessage))
                ->willReturn($builder);
        } else {
            $context->expects($this->never())
                ->method('buildViolation');
        }

        $validator = new CollectionOfUniqueElementsValidator();
        $validator->initialize($context);

        return $validator;
    }
}
