<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Validator;

use Sofyco\Bundle\WordPressGatewayBundle\Repository\SiteRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class UniqueDomainValidator extends ConstraintValidator
{
    public function __construct(private readonly SiteRepositoryInterface $siteRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueDomain) {
            throw new UnexpectedTypeException($constraint, UniqueDomain::class);
        }

        if (false === is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if ($this->siteRepository->isExists(url: $value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
