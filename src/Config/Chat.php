<?php namespace Tatter\Chat\Config;

use CodeIgniter\Config\BaseConfig;

class Chat extends BaseConfig
{
	// Whether to continue instead of throwing exceptions
	public $silent = true;

	// The session variable to check for a logged-in user ID
	public $userSource = 'logged_in';

	// Handler to use for retrieving user accounts
	public $accountHandler = 'Tatter\Accounts\Handlers\MythHandler';
}
