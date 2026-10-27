<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Commands;

use Illuminate\Support\Facades\File;
use N3XT0R\FilamentPassportUi\Tests\TestCase;

final class InstallCommandTest extends TestCase
{
    public function testInstallPublishesCoreMigrations(): void
    {
        $migrationsPath = database_path('migrations');
        $configPath = config_path('passport-ui.php');
        $configExisted = is_file($configPath);
        $configBackup = $configExisted ? File::get($configPath) : null;

        $suffixes = [
            'create_passport_scope_resources_table.php',
            'create_passport_scope_actions_table.php',
            'create_passport_scope_grant_table.php',
            'add_client_id_to_passport_scope_grants_table.php',
            'change_passport_scope_grant_unique_index.php',
            'fix_passport_scope_grants_context_client_id_type.php',
        ];

        $stashed = $this->stashMigrations($migrationsPath, $suffixes);

        try {
            $this->artisan('filament-passport-ui:install')
                ->expectsConfirmation('Would you like to run the migrations now?', 'no')
                ->expectsConfirmation('Would you like to star our repo on GitHub?', 'no')
                ->expectsConfirmation('Do you want to seed default Passport scope resources and actions?', 'no')
                ->assertSuccessful();

            $published = collect(File::files($migrationsPath))->map->getFilename();

            foreach ($suffixes as $suffix) {
                self::assertTrue(
                    $published->contains(static fn (string $file): bool => str_ends_with($file, $suffix)),
                    'Missing published migration ' . $suffix,
                );
            }
        } finally {
            $this->restoreMigrations($migrationsPath, $suffixes, $stashed);

            if ($configExisted) {
                File::put($configPath, $configBackup);
            } elseif (is_file($configPath)) {
                File::delete($configPath);
            }
        }
    }

    /**
     * @param  array<int, string>  $suffixes
     * @return array<string, string>
     */
    private function stashMigrations(string $migrationsPath, array $suffixes): array
    {
        $stashed = [];

        foreach (File::files($migrationsPath) as $file) {
            foreach ($suffixes as $suffix) {
                if (! str_ends_with($file->getFilename(), $suffix)) {
                    continue;
                }

                $stashed[$file->getFilename()] = File::get($file->getPathname());
                File::delete($file->getPathname());
            }
        }

        return $stashed;
    }

    /**
     * @param  array<int, string>  $suffixes
     * @param  array<string, string>  $stashed
     */
    private function restoreMigrations(string $migrationsPath, array $suffixes, array $stashed): void
    {
        foreach (File::files($migrationsPath) as $file) {
            foreach ($suffixes as $suffix) {
                if (str_ends_with($file->getFilename(), $suffix)) {
                    File::delete($file->getPathname());
                }
            }
        }

        foreach ($stashed as $filename => $contents) {
            File::put($migrationsPath . DIRECTORY_SEPARATOR . $filename, $contents);
        }
    }
}
