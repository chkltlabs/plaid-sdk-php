<?php

namespace ChkltLabs\Plaid\Resources;

use ChkltLabs\Plaid\PlaidRequestException;

class Categories extends AbstractResource
{
	/**
	 * Get all Plaid categories.
	 *
	 * @throws PlaidRequestException
	 * @return object
	 */
	public function list(): object
	{
		return $this->sendRequest("post", "categories/get");
	}
}