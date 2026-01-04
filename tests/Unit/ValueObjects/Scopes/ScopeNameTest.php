<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Unit\ValueObjects\Scopes;

use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\ValueObjects\Scopes\ScopeName;
use PHPUnit\Framework\TestCase;

final class ScopeNameTest extends TestCase
{
    public function testItCreatesScopeNameFromModels(): void
    {
        $resource = new PassportScopeResource([
            'name' => 'users',
            'description' => 'User management',
        ]);

        $action = new PassportScopeAction([
            'name' => 'update',
            'description' => 'Update user details',
        ]);

        $scopeName = ScopeName::from($resource, $action);

        self::assertSame('users:update', $scopeName->value());
        self::assertSame(
            'User management: Update user details',
            $scopeName->description()
        );
    }

    public function testDescriptionIsTrimmedCorrectly(): void
    {
        $resource = new PassportScopeResource([
            'name' => 'posts',
            'description' => '  Blog posts  ',
        ]);

        $action = new PassportScopeAction([
            'name' => 'read',
            'description' => 'Read blog posts',
        ]);

        $scopeName = ScopeName::from($resource, $action);

        self::assertSame(
            'Blog posts: Read blog posts',
            $scopeName->description()
        );
    }

    public function testItCanBeCastToString(): void
    {
        $resource = new PassportScopeResource([
            'name' => 'comments',
            'description' => 'Comments',
        ]);

        $action = new PassportScopeAction([
            'name' => 'delete',
            'description' => 'Delete comments',
        ]);

        $scopeName = ScopeName::from($resource, $action);

        self::assertSame('comments:delete', (string)$scopeName);
    }
}
