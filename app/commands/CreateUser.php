<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateUser extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'bestline:create-user';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new user in the application';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$user = new User();
		
		$user->username = $this->argument('username');
		$user->email = $this->argument('email');
		$user->first_name = $this->argument('first_name');
		$user->last_name = $this->argument('last_name');
		$user->password = $this->argument('password');
		
		$user->save();
		
		$this->info("Created user '{$user->first_name} {$user->last_name}'");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('username', InputArgument::REQUIRED, 'The Username'),
		    array('email', InputArgument::REQUIRED, 'E-mail'),
		    array('first_name', InputArgument::REQUIRED, 'First Name'),
		    array('last_name', InputArgument::REQUIRED, 'Last Name'),
		    array('password', InputArgument::REQUIRED, 'Password'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
