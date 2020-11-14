<?php

namespace TomorrowIdeas\Plaid\Tests;

use ReflectionClass;
use Shuttle\Shuttle;
use TomorrowIdeas\Plaid\Plaid;
use UnexpectedValueException;

/**
 * @covers \TomorrowIdeas\Plaid\Plaid
 * @covers \TomorrowIdeas\Plaid\Resources\AbstractResource
 * @covers \TomorrowIdeas\Plaid\Resources\Items
 */
class PlaidClientTest extends TestCase
{
	public function test_default_environment_is_production()
	{
		$plaid = new Plaid("client_id", "secret");

		$reflectionClass = new ReflectionClass($plaid);
		$reflectionProperty = $reflectionClass->getProperty("environment");
		$reflectionProperty->setAccessible(true);

		$this->assertEquals("production", $reflectionProperty->getValue($plaid));
	}

	public function test_setting_invalid_environment_throws()
	{
		$this->expectException(UnexpectedValueException::class);
		$plaid = new Plaid("client_id", "secret", "invalid_environment");
	}

	public function test_production_host()
	{
		$plaidClient = $this->getPlaidClient("production");

		$response = $plaidClient->items->getItem("access_token");

		$this->assertEquals("https", $response->scheme);
		$this->assertEquals("production.plaid.com", $response->host);
	}

	public function test_development_host()
	{
		$plaidClient = $this->getPlaidClient("development");
		$response = $plaidClient->items->getItem("access_token");

		$this->assertEquals("https", $response->scheme);
		$this->assertEquals("development.plaid.com", $response->host);
	}

	public function test_sandbox_host()
	{
		$plaidClient = $this->getPlaidClient("sandbox");
		$response = $plaidClient->items->getItem("access_token");

		$this->assertEquals("https", $response->scheme);
		$this->assertEquals("sandbox.plaid.com", $response->host);
	}

	public function test_default_plaid_version()
	{
		$this->assertEquals("2020-09-14", Plaid::API_VERSION);
	}

	public function test_setting_http_client()
	{
		$httpClient = new Shuttle;

		$plaid = new Plaid("client_id", "secret");
		$plaid->setHttpClient($httpClient);

		$reflection = new \ReflectionClass($plaid);

		$method = $reflection->getMethod('getHttpClient');
		$method->setAccessible(true);

		$this->assertSame($httpClient, $method->invoke($plaid));
	}

	public function test_getting_http_client_creates_default_client_if_none_set()
	{
		$plaid = new Plaid("client_id", "secret");

		$reflection = new \ReflectionClass($plaid);

		$method = $reflection->getMethod('getHttpClient');
		$method->setAccessible(true);

		$this->assertInstanceOf(Shuttle::class, $method->invoke($plaid));
	}
}