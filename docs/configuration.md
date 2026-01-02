# Configuration

This document explains how to configure Filament Passport UI after installation. It covers both the package-provided
installer and the alternative Composer installation (useful when distributing the package with Spatie's Package Tools).

1. Installation

```bash
composer require n3xt0r/filament-passport-ui
```

## Option A — Use the package installer (recommended when available):

```bash
php artisan filament-passport-ui:install
```

This command will publish the package configuration to your application's `config` directory and perform any other
installation tasks the package provides.

## Option B - Install via Composer (for packages published on Packagist or when using Spatie's Package Tools):

If the package does not automatically publish configuration or other assets, run vendor:publish. Replace the provider
and tag placeholders with the package's actual ServiceProvider class and publish tag (check the package README if
unsure):

```bash
php artisan vendor:publish --provider="N3XT0R\FilamentPassportUiServiceProvider" --tag="config"
# optional: publish other tags if provided, e.g. migrations, views, assets
php artisan vendor:publish --provider="N3XT0R\FilamentPassportUiServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="N3XT0R\FilamentPassportUiServiceProvider" --tag="assets"
```

2. Configuration file

After publishing, the main configuration file will typically appear at `config/passport-ui.php`. Edit this file
to adjust package-specific settings such as route prefixes, guard settings, and UI-related options.

3. Verify

- Check that `config/passport-ui.php` exists and contains the expected keys.

