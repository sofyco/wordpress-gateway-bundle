<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Validator;

use Sofyco\Bundle\WordPressGatewayBundle\Gateway\WordPressGateway;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class UniqueDomainValidator extends ConstraintValidator
{
    public function __construct(private readonly WordPressGateway $wordPressGateway)
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

        if ($this->wordPressGateway->isSiteExists(baseUrl: $value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
