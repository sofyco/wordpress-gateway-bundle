<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class UniqueDomain extends Constraint
{
    public function __construct(public string $message = 'document.exists')
    {
        parent::__construct();
    }
}
