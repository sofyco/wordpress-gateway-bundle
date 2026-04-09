<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Tests\Validator;

use Sofyco\Bundle\WordPressGatewayBundle\Tests\Fixtures\InMemorySiteRepository;
use Sofyco\Bundle\WordPressGatewayBundle\Validator\UniqueDomain;
use Sofyco\Bundle\WordPressGatewayBundle\Validator\UniqueDomainValidator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<UniqueDomainValidator>
 */
final class UniqueDomainValidatorTest extends ConstraintValidatorTestCase
{
    private InMemorySiteRepository $siteRepository;

    protected function setUp(): void
    {
        $this->siteRepository = new InMemorySiteRepository();

        parent::setUp();
    }

    public function testInvalidConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $this->validator->validate('https://example.com', new NotBlank());
    }

    public function testInvalidValueType(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $this->validator->validate(123, new UniqueDomain());
    }

    public function testValidationFailsWhenSiteAlreadyExists(): void
    {
        $constraint = new UniqueDomain();
        $this->siteRepository->existingUrls = ['https://example.com'];

        $this->validator->validate('https://example.com', $constraint);

        $this->buildViolation($constraint->message)->assertRaised();
    }

    public function testValidationPassesWhenSiteDoesNotExist(): void
    {
        $this->siteRepository->existingUrls = [];

        $this->validator->validate('https://example.com', new UniqueDomain());

        $this->assertNoViolation();
    }

    protected function createValidator(): UniqueDomainValidator
    {
        return new UniqueDomainValidator($this->siteRepository);
    }
}
