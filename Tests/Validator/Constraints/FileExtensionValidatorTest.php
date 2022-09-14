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
 * @author Tomasz Gemza
 */
class FileExtensionValidatorTest extends TestCase
{
    /**
     * @dataProvider getValidValues
     */
    public function testValidValues(array $validExtensions, $file, ?bool $matchCase = false): void
    {
        $validator = $this->configureValidator();
        $validator->validate($file, new FileExtension($validExtensions, null, $matchCase));
    }

    /**
     * @dataProvider getInvalidValues
     */
    public function testInvalidValues(array $validExtensions, mixed $file, ?bool $matchCase = false): void
    {
        $constraint = new FileExtension($validExtensions, null, $matchCase);

        $validator = $this->configureValidator($constraint->message);
        $validator->validate($file, $constraint);
    }

    /**
     * @dataProvider getInvalidValues
     */
    public function testAllowedValues(array $validExtensions, mixed $file, ?bool $matchCase = false): void
    {
        $constraint = new FileExtension($validExtensions, null, $matchCase);

        $validator = $this->configureValidator();
        $validator->validate($file, $constraint);
    }

    /**
     * @dataProvider getValidValues
     */
    public function testDisallowedValues(array $disallowedExtensions, mixed $file, ?bool $matchCase = false): void
    {
        $constraint = new FileExtension(null, $disallowedExtensions, $matchCase);

        $validator = $this->configureValidator($constraint->disallowedMessage);
        $validator->validate($file, $constraint);
    }

    public function getValidValues(): array
    {
        $uploadedFile = new UploadedFile(__FILE__, 'test.pdf', null, null, true);
        $notUploadedFile = new UploadedFile(__FILE__, 'test.pdf', null, UPLOAD_ERR_FORM_SIZE, true);

        return [
            [['png', 'jpg', 'gif'], 'test.png'],
            [['png', 'jpg', 'gif'], 'test.png'],
            [['png', 'JPG', 'gif'], 'test.JPG', false],
            [['png', 'JPG', 'gif'], 'test.gif', false],
            [['jpg'], '/path/to/file/test.jpg'],
            [['php'], new \SplFileInfo(__FILE__)],
            [['php'], new File(__FILE__)],
            [['pdf'], $uploadedFile],
            [['pdf'], $notUploadedFile],
            [['png', 'jpg', 'gif'], ['test.png', 'test.jpg']],
            [['png', 'jpg', 'gif'], ['test.png', 'test.jpg']],
            [['png', 'JPG', 'gif'], ['test.JPG', 'test.GIF'], false],
            [['png', 'JPG', 'gif'], ['test.jpg', 'test.gif'], false],
            [['jpg'], ['/path/to/file/test1.jpg', '/path/to/file/test2.jpg']],
            [['php'], [new \SplFileInfo(__FILE__), new \SplFileInfo(__FILE__)]],
            [['php'], [new File(__FILE__), new File(__FILE__)]],
            [['pdf'], [$uploadedFile, $uploadedFile]],
            [['pdf'], [$notUploadedFile, $notUploadedFile]],
        ];
    }

    public function getInvalidValues(): array
    {
        $uploadedFile = new UploadedFile(__FILE__, 'test.pdf', null, null, true);
        $notUploadedFile = new UploadedFile(__FILE__, 'test.pdf', null, UPLOAD_ERR_FORM_SIZE, true);

        return [
            [['png'], 'gif'],
            [['png', 'JPG', 'gif'], 'test.jpg', true],
            [['png', 'JPG', 'gif'], 'test.GIF', true],
            [['txt', 'mp3'], 'test.png'],
            [['txt'], '/path/to/file/test.jpg'],
            [['mp4'], new \SplFileInfo(__FILE__)],
            [['mp4'], new File(__FILE__)],
            [['php'], $uploadedFile],
            [['php'], $notUploadedFile],
            [['png'], ['gif', 'jpg']],
            [['png', 'JPG', 'gif'], ['test.jpg', 'test.GIF'], true],
            [['png', 'JPG', 'gif'], ['test.PNG', 'test.GIF'], true],
            [['txt', 'mp3'], ['test.png', 'test.jpg']],
            [['txt'], ['/path/to/file/test.jpg', '/path/to/file/test.gif']],
            [['mp4'], [new \SplFileInfo(__FILE__), new \SplFileInfo(__FILE__)]],
            [['mp4'], [new File(__FILE__), new File(__FILE__)]],
            [['php'], [$uploadedFile, $uploadedFile]],
            [['php'], [$notUploadedFile, $notUploadedFile]],
        ];
    }

    private function configureValidator(?string $expectedMessage = null): FileExtensionValidator
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
            $builder->expects($this->atLeastOnce())
                ->method('addViolation');

            $context->expects($this->atLeastOnce())
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
