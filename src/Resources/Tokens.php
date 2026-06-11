<?php

namespace ChkltLabs\Plaid\Resources;

use ChkltLabs\Plaid\Entities\User;
use ChkltLabs\Plaid\PlaidRequestException;
use ChkltLabs\Plaid\Entities\AccountFilters;

class Tokens extends AbstractResource
{
	/**
	 * Create a Link Token.
	 *
	 * @param string $client_name
	 * @param string $language Possible values are: en, fr, es, nl
	 * @param array<string> $country_codes Possible values are: CA, FR, IE, NL, ES, GB, US
	 * @param User $user
	 * @param array<string> $products Possible values are: transactions, auth, identity, income, assets, investments, liabilities, payment_initiation
	 * @param array<string> $required_if_supported_products Possible values are: transactions, auth, identity, income, assets, investments, liabilities, payment_initiation
	 * @param array<string> $optional_products Possible values are: transactions, auth, identity, income, assets, investments, liabilities, payment_initiation
	 * @param array<string> $additional_consented_products Possible values are: transactions, auth, identity, income, assets, investments, liabilities, payment_initiation
	 * @param string|null $webhook
	 * @param string|null $access_token
	 * @param string|null $link_customization_name
	 * @param string|null $redirect_uri
	 * @param string|null $android_package_name
	 * @param array|null $institution_data
	 * @param AccountFilters|null $account_filters
	 * @param array|null $eu_config
	 * @param string|null $institution_id
	 * @param array|null $payment_initiation
	 * @param array|null $income_verification
	 * @param array|null $cra_options
	 * @param string|null $consumer_report_permissible_purpose
	 * @param array|null $auth
	 * @param array|null $transfer
	 * @param array|null $update
	 * @param array|null $identity_verification
	 * @param array|null $statements
	 * @param string|null $user_token
	 * @param array|null $investments
	 * @param array|null $investments_auth
	 * @param array|null $hosted_link
	 * @param array|null $transactions
	 * @param array|null $identity
	 * @param bool|null $enable_multi_item_link
	 * @param string|null $user_id
	 * @return object
	 * @throws PlaidRequestException
	 */
	public function create(
		string $client_name,
		string $language,
		array $country_codes,
		User $user,
		?array $products = [],
		array $required_if_supported_products = [],
		array $optional_products = [],
		array $additional_consented_products = [],
		?string $webhook = null,
		?string $access_token = null,
		?string $link_customization_name = null,
		?string $redirect_uri = null,
		?string $android_package_name = null,
		?array $institution_data = null,
		?AccountFilters $account_filters = null,
		?array $eu_config = null,
		?string $institution_id = null,
		?array $payment_initiation = null,
		?array $income_verification = null,
		?array $cra_options = null,
		?string $consumer_report_permissible_purpose = null,
		?array $auth = null,
		?array $transfer = null,
		?array $update = null,
		?array $identity_verification = null,
		?array $statements = null,
		?string $user_token = null,
		?array $investments = null,
		?array $investments_auth = null,
		?array $hosted_link = null,
		?array $transactions = null,
		?array $identity = null,
		?bool $enable_multi_item_link = null,
		?string $user_id = null
	): object {

		//all defined params show here, including ones left null
		//so, filter out the nulls
		$params = array_filter(get_defined_vars(), function ($var) {
			return !empty($var);
		});

		//perform any data transformation
		$params["user"] = $params["user"]->toArray();

		if( isset($params["account_filters"]) ){
			$params["account_filters"] = $params["account_filters"]->toArray();
		}

		if( isset($params["payment_id"]) ){
			$params["payment_initiation"] = [
				"payment_id" => $params["payment_id"]
			];
			unset($params["payment_id"]);
		}

		//pass to plaid
		return $this->sendRequest(
			"post",
			"link/token/create",
			$this->paramsWithClientCredentials($params)
		);
	}

	/**
	 * Get information about a previously created Link token.
	 *
	 * @param string $link_token
	 * @throws PlaidRequestException
	 * @return object
	 */
	public function get(string $link_token): object
	{
		$params = [
			"link_token" => $link_token
		];

		return $this->sendRequest(
			"post",
			"link/token/get",
			$this->paramsWithClientCredentials($params)
		);
	}
}