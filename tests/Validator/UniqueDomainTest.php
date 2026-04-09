<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Sofyco\Bundle\WordPressGatewayBundle\Validator\UniqueDomain;
use Symfony\Component\Validator\Constraint;

final class UniqueDomainTest extends TestCase
{
    public function testDefaultMessageIsSet(): void
    {
        $constraint = new UniqueDomain();

        self::assertSame('document.exists', $constraint->message);
    }

    public function testCustomMessageCanBeProvided(): void
    {
        $constraint = new UniqueDomain('custom.message');

        self::assertSame('custom.message', $constraint->message);
    }

    public function testItIsSymfonyConstraint(): void
    {
        self::assertInstanceOf(Constraint::class, new UniqueDomain());
    }
}
