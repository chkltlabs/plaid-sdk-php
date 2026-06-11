# Upgrade Guide: 1.x to 2.0

Version 2.0 is a major release that renames the package, modernizes the HTTP stack for Laravel 13 / PHP 8.2+ compatibility, and decouples request building from a concrete PSR-7 implementation.

The original [`tomorrow-ideas/plaid-sdk-php`](https://packagist.org/packages/tomorrow-ideas/plaid-sdk-php) package is abandoned. New development continues as [`chkltlabs/plaid-sdk-php`](https://packagist.org/packages/chkltlabs/plaid-sdk-php).

## Summary

| 1.x (`tomorrow-ideas/plaid-sdk-php`) | 2.x (`chkltlabs/plaid-sdk-php`) |
|--------------------------------------|----------------------------------|
| `composer require tomorrow-ideas/plaid-sdk-php` | `composer require chkltlabs/plaid-sdk-php` |
| `TomorrowIdeas\Plaid\*` | `ChkltLabs\Plaid\*` |
| PHP 7.3+ | PHP 8.2+ |
| `nimbly/shuttle ^0.4` | `nimbly/shuttle ^2.0` |
| `Shuttle\Shuttle` | `Nimbly\Shuttle\Shuttle` |
| `new Shuttle(['handler' => $h])` | `new Shuttle(handler: $h)` |
| `Capsule\Request` in SDK internals | PSR-17 factories (injectable via `Plaid`) |
| Transitive PSR-7 v1 | Explicit `psr/http-message ^2.0` |

## Installation

Remove the old package and require the new one:

```bash
composer remove tomorrow-ideas/plaid-sdk-php
composer require chkltlabs/plaid-sdk-php:^2.0
```

## Namespace changes

Update all imports and fully qualified class names:

```php
// Before
use TomorrowIdeas\Plaid\Plaid;

// After
use ChkltLabs\Plaid\Plaid;
```

## HTTP client changes

Shuttle 2 uses the `Nimbly\Shuttle` namespace and named constructor arguments:

```php
// Before
use Shuttle\Handler\MockHandler;
use Shuttle\Shuttle;

$client = new Shuttle(['handler' => new MockHandler([...])]);

// After
use Nimbly\Shuttle\Handler\MockHandler;
use Nimbly\Shuttle\Shuttle;

$client = new Shuttle(handler: new MockHandler([...]));
```

If you instantiate `Capsule` classes directly in tests or application code, update those imports as well:

```php
// Before
use Capsule\Request;
use Capsule\Response;

// After
use Nimbly\Capsule\Request;
use Nimbly\Capsule\Response;
```

## PSR-17 request factories

The SDK no longer constructs `Capsule\Request` directly inside resource classes. Request bodies are built with injectable PSR-17 factories on the `Plaid` client:

```php
use ChkltLabs\Plaid\Plaid;
use GuzzleHttp\Psr7\HttpFactory;

$plaid = new Plaid($clientId, $secret, 'sandbox');

$httpFactory = new HttpFactory;
$plaid->setRequestFactory($httpFactory);
$plaid->setStreamFactory($httpFactory);
```

If you do not set custom factories, the SDK defaults to `Nimbly\Capsule\Factory\RequestFactory` and `Nimbly\Capsule\Factory\StreamFactory`.

## PSR-7 v2

Version 2.0 requires `psr/http-message ^2.0`. If your application pins PSR-7 v1, upgrade to a v2-compatible implementation before adopting this release.

## Laravel 13

Laravel 13 requires PHP 8.3. This SDK requires PHP 8.2+, so it installs cleanly in Laravel 13 applications. The default HTTP client remains Shuttle 2, which satisfies the `nimbly/shuttle ^2.0` constraint used by modern PHP ecosystems.
