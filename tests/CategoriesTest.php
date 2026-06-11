<?php

namespace ChkltLabs\Plaid\Tests;

/**
 * @covers ChkltLabs\Plaid\Plaid
 * @covers ChkltLabs\Plaid\Resources\AbstractResource
 * @covers ChkltLabs\Plaid\Resources\Categories
 */
class CategoriesTest extends TestCase
{
	public function test_get_identity(): void
	{
		$response = $this->getPlaidClient()->categories->list();

		$this->assertEquals("POST", $response->method);
		$this->assertEquals("2020-09-14", $response->version);
		$this->assertEquals("application/json", $response->content);
		$this->assertEquals("/categories/get", $response->path);
	}
}