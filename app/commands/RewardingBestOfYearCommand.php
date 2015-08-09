<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RewardingBestOfYearCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'reward:year';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Rewarding the best users of the year.';

	/**
	 * Create a new command instance.
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
		$lastYear = date_create(date('d-m-Y') . ' first day of last year');
		$year = $lastYear->format('Y');

		$bestWriter = User::getBestWriterOfYear($year, 1);
		$bestRespondent = User::getBestRespondentOfYear($year, 1);
		$bestCommentator = User::getBestCommentatorOfYear($year, 1);

		if($bestWriter->first()) {
			$user = $bestWriter->first();
			$honor = Honor::whereKey('bestWriterOfYear')->first();

			if($honor) {
				$this->info('Best Writer: ' . $user->login);

				$data = array(
					'user_id' => $user->id,
					'honor_id' => $honor->id,
					'comment' => 'Лучший писатель за '.
						$lastYear->format('Y') .' год.'
				);

				$userHonor = UserHonor::whereHonorId($honor->id)
					->where('created_at', '>=', \Carbon\Carbon::create(date('Y'), 1, 1, 00, 00, 00)->subYear())
					->first();

				if (!$userHonor) {
					if(UserHonor::create($data)){
						$this->info(' -- Best Writer ('. $user->login .') of the '. $lastYear->format('Y') .' year was rewarded.');
					}
				} else {
					$this->info(' -- Best Writer of the '. $lastYear->format('Y') .' year already rewarded ('. $userHonor->user->login .').');
				}
			} else {
				$this->error(' -- Best Writer of the '. $lastYear->format('Y') .' year not rewarded (reward not found).');
			}
		} else {
			$this->info('Best Writer of the '. $lastYear->format('Y') .' year not found.');
		}

		if($bestRespondent->first()) {
			$user = $bestRespondent->first();
			$honor = Honor::whereKey('bestRespondentOfYear')->first();

			if($honor) {
				$this->info('Best Respondent: ' . $user->login);

				$data = array(
					'user_id' => $user->id,
					'honor_id' => $honor->id,
					'comment' => 'Лучший советчик за '.
						$lastYear->format('Y') .' год.'
				);

				$userHonor = UserHonor::whereHonorId($honor->id)
					->where('created_at', '>=', \Carbon\Carbon::create(date('Y'), 1, 1, 00, 00, 00)->subYear())
					->first();

				if (!$userHonor) {
					if(UserHonor::create($data)){
						$this->info(' -- Best Respondent ('. $user->login .') of the '. $lastYear->format('Y') .' year was rewarded.');
					}
				} else {
					$this->info(' -- Best Respondent of the '. $lastYear->format('Y') .' year already rewarded ('. $userHonor->user->login .').');
				}
			} else {
				$this->error(' -- Best Respondent of the '. $lastYear->format('Y') .' year not rewarded (reward not found).');
			}
		} else {
			$this->info('Best Respondent of the '. $lastYear->format('Y') .' year not found.');
		}

		if($bestCommentator->first()) {
			$user = $bestCommentator->first();
			$honor = Honor::whereKey('bestCommentatorOfYear')->first();

			if($honor) {
				$this->info('Best Commentator: ' . $user->login);

				$data = array(
					'user_id' => $user->id,
					'honor_id' => $honor->id,
					'comment' => 'Лучший комментатор за '.
						$lastYear->format('Y') .' год.'
				);

				$userHonor = UserHonor::whereHonorId($honor->id)
					->where('created_at', '>=', \Carbon\Carbon::create(date('Y'), 1, 1, 00, 00, 00)->subYear())
					->first();

				if (!$userHonor) {
					if(UserHonor::create($data)){
						$this->info(' -- Best Commentator ('. $user->login .') of the '. $lastYear->format('Y') .' year was rewarded.');
					}
				} else {
					$this->info(' -- Best Commentator of the '. $lastYear->format('Y') .' year already rewarded ('. $userHonor->user->login .').');
				}
			} else {
				$this->error(' -- Best Commentator of the '. $lastYear->format('Y') .' year not rewarded (reward not found).');
			}
		} else {
			$this->info('Best Commentator of the '. $lastYear->format('Y') .' year not found.');
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
