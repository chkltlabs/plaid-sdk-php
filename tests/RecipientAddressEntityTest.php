<?php

namespace ChkltLabs\Plaid\Tests;

use ChkltLabs\Plaid\Entities\RecipientAddress;

/**
 * @covers ChkltLabs\Plaid\Entities\RecipientAddress
 */
class RecipientAddressEntityTest extends TestCase
{
	public function test_to_array_with_single_street(): void
	{
		$address = new RecipientAddress("123 Elm St", null, "Anytown", "ABC 123", "US");

		$this->assertEquals(
			[
				"street" => ["123 Elm St"],
				"city" => "Anytown",
				"postal_code" => "ABC 123",
				"country" => "US"
			],
			$address->toArray()
		);
	}

	public function test_to_array_with_additional_street(): void
	{
		$address = new RecipientAddress("123 Elm St", "Apt A", "Anytown", "ABC 123", "US");

		$this->assertEquals(
			[
				"street" => ["123 Elm St", "Apt A"],
				"city" => "Anytown",
				"postal_code" => "ABC 123",
				"country" => "US"
			],
			$address->toArray()
		);
	}
}