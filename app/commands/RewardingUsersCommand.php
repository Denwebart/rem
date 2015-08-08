<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RewardingUsersCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'reward';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Rewarding the best users of the month.';

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
		$bestWriter = User::getBestWriter(null, null, 1);
		$bestRespondent = User::getBestRespondent(null, null, 1);
		$bestCommentator = User::getBestCommentator(null, null, 1);

		if($bestWriter->first()) {
			$this->info('Best Writer: ' . $bestWriter->first()->login);
		}
		if($bestWriter->first()) {
			$this->comment('Best Respondent: ' . $bestRespondent->first()->login);
		}
		if($bestWriter->first()) {
			$this->question('Best Commentator: ' . $bestCommentator->first()->login);
		}

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
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
//			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
