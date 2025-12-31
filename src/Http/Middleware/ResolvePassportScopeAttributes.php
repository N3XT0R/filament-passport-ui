<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Laravel\Passport\Http\Middleware\CheckToken;
use N3XT0R\FilamentPassportUi\Support\Attributes\RequiresAnyScope;
use N3XT0R\FilamentPassportUi\Support\Attributes\RequiresScope;
use N3XT0R\FilamentPassportUi\Support\Passport\Traits\HasPassportScopes;
use N3XT0R\FilamentPassportUi\Support\Passport\Traits\HasPassportScopesTrait;
use ReflectionMethod;

final class ResolvePassportScopeAttributes
{
    public function handle(Request $request, Closure $next)
    {
        $route = $request->route();

        if (!$route instanceof Route) {
            return $next($request);
        }

        $this->applyAttributes($route);

        return $next($request);
    }

    private function applyAttributes(Route $route): void
    {
        [$controller, $method] = $route->getAction('controller') ?? [null, null];

        if (!is_string($controller) || !method_exists($controller, $method)) {
            return;
        }

        $reflection = new ReflectionMethod($controller, $method);

        foreach ($reflection->getAttributes() as $attribute) {
            $instance = $attribute->newInstance();

            match (true) {
                $instance instanceof RequiresScope =>
                $route->middleware(
                    HasPassportScopes::requires(...$instance->scopes)
                ),

                $instance instanceof RequiresAnyScope =>
                $route->middleware(
                    HasPassportScopes::requiresAny(...$instance->scopes)
                ),

                default => null,
            };
        }
    }
}
