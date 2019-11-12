<?php

namespace SecIT\ValidationBundle\Tests\Validator\Constraints;

use PHPUnit\Framework\TestCase;
use SecIT\ValidationBundle\Validator\Constraints\FileExtension;
use SecIT\ValidationBundle\Validator\Constraints\FileExtensionValidator;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

/**
 * Class FileExtensionValidatorTest.
 *
 * @author Tomasz Gemza
 */
class FileExtensionValidatorTest extends TestCase
{
    /**
     * Test valid values.
     *
     * @param array $validExtensions
     * @param mixed $file
     * @param bool  $matchCase
     *
     * @dataProvider getValidValues
     */
    public function testValidValues(array $validExtensions, $file, ?bool $matchCase = false): void
    {
        $constraint = new FileExtension();
        $constraint->validExtensions = $validExtensions;
        $constraint->matchCase = $matchCase;

        $validator = $this->configureValidator();
        $validator->validate($file, $constraint);
    }

    /**
     * Test invalid values.
     *
     * @param array $validExtensions
     * @param mixed $file
     * @param bool  $matchCase
     *
     * @dataProvider getInvalidValues
     */
    public function testInvalidValues(array $validExtensions, $file, ?bool $matchCase = false): void
    {
        $constraint = new FileExtension();
        $constraint->validExtensions = $validExtensions;
        $constraint->matchCase = $matchCase;

        $validator = $this->configureValidator($constraint->message);
        $validator->validate($file, $constraint);
    }
    /**
     * Test allowed values.
     *
     * @param array $validExtensions
     * @param mixed $file
     * @param bool  $matchCase
     *
     * @dataProvider getInvalidValues
     */
    public function testAllowedValues(array $validExtensions, $file, ?bool $matchCase = false): void
    {
        $constraint = new FileExtension();
        $constraint->disallowedExtensions = $validExtensions;
        $constraint->matchCase = $matchCase;

        $validator = $this->configureValidator();
        $validator->validate($file, $constraint);
    }

    /**
     * Test disallowed values.
     *
     * @param array $validExtensions
     * @param mixed $file
     * @param bool  $matchCase
     *
     * @dataProvider getValidValues
     */
    public function testDisallowedValues(array $validExtensions, $file, ?bool $matchCase = false): void
    {
        $constraint = new FileExtension();
        $constraint->disallowedExtensions = $validExtensions;
        $constraint->matchCase = $matchCase;

        $validator = $this->configureValidator($constraint->disallowedMessage);
        $validator->validate($file, $constraint);
    }

    /**
     * Invalid values.
     *
     * @return array
     */
    public function getValidValues(): array
    {
        $uploadedFile = new UploadedFile(__FILE__, 'test.pdf', null, null, true);

        return [
            [['png', 'jpg', 'gif'], 'test.png'],
            [['png', 'jpg', 'gif'], 'test.png'],
            [['png', 'JPG', 'gif'], 'test.JPG', false],
            [['png', 'JPG', 'gif'], 'test.gif', false],
            [['jpg'], '/path/to/file/test.jpg'],
            [['php'], new \SplFileInfo(__FILE__)],
            [['php'], new File(__FILE__)],
            [['pdf'], $uploadedFile],
        ];
    }

    /**
     * Valid values.
     *
     * @return array
     */
    public function getInvalidValues(): array
    {
        $uploadedFile = new UploadedFile(__FILE__, 'test.pdf', null, null, true);

        return [
            [['png'], 'gif'],
            [['png', 'JPG', 'gif'], 'test.jpg', true],
            [['png', 'JPG', 'gif'], 'test.GIF', true],
            [['txt', 'mp3'], 'test.png'],
            [['txt'], '/path/to/file/test.jpg'],
            [['mp4'], new \SplFileInfo(__FILE__)],
            [['mp4'], new File(__FILE__)],
            [['php'], $uploadedFile],
        ];
    }

    /**
     * Configure validator.
     *
     * @param null $expectedMessage
     *
     * @return FileExtensionValidator
     */
    private function configureValidator($expectedMessage = null): FileExtensionValidator
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

        $validator = new FileExtensionValidator();
        $validator->initialize($context);

        return $validator;
    }
}
