<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RewardingBestOfMonthCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'reward:month';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Rewarding the best users of the month.';

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
		$lastMonth = date_create(date('d-m-Y') . ' first day of last month');
		$month = $lastMonth->format('m');
		$year = $lastMonth->format('Y');

		$bestWriter = User::getBestWriter($year, $month, 1);
		$bestRespondent = User::getBestRespondent($year, $month, 1);
		$bestCommentator = User::getBestCommentator($year, $month, 1);

		if($bestWriter->first()) {
			$user = $bestWriter->first();
			$honor = Honor::whereKey('bestWriterOfMonth')->first();

			if($honor) {
				$this->info('Best Writer: ' . $user->login);

				$data = array(
					'user_id' => $user->id,
					'honor_id' => $honor->id,
					'comment' => 'Лучший писатель за '.
						mb_strtolower(DateHelper::$monthsList[$lastMonth->format('n')]) . ' ' .
						$lastMonth->format('Y') .' года.'
				);

				$userHonor = UserHonor::whereHonorId($honor->id)
					->where('created_at', '>=', \Carbon\Carbon::now()->subMonth())
					->first();

				if (!$userHonor) {
					if(UserHonor::create($data)){
						$this->info(' -- Best Writer ('. $user->login .') of the '. $lastMonth->format('M Y') .' year was rewarded.');
					}
				} else {
					$this->info(' -- Best Writer of the '. $lastMonth->format('M Y') .' year already rewarded ('. $userHonor->user->login .').');
				}
			} else {
				$this->error(' -- Best Writer of the '. $lastMonth->format('M Y') .' year not rewarded (reward not found).');
			}
		} else {
			$this->info('Best Writer of the '. $lastMonth->format('M Y') .' year not found.');
		}

		if($bestRespondent->first()) {
			$user = $bestRespondent->first();
			$honor = Honor::whereKey('bestRespondentOfMonth')->first();

			if($honor) {
				$this->info('Best Respondent: ' . $user->login);

				$data = array(
					'user_id' => $user->id,
					'honor_id' => $honor->id,
					'comment' => 'Лучший советчик за '.
						mb_strtolower(DateHelper::$monthsList[$lastMonth->format('n')]) . ' ' .
						$lastMonth->format('Y') .' года.'
				);

				$userHonor = UserHonor::whereHonorId($honor->id)
					->where('created_at', '>=', \Carbon\Carbon::now()->subMonth())
					->first();

				if (!$userHonor) {
					if(UserHonor::create($data)){
						$this->info(' -- Best Respondent ('. $user->login .') of the '. $lastMonth->format('M Y') .' year was rewarded.');
					}
				} else {
					$this->info(' -- Best Respondent of the '. $lastMonth->format('M Y') .' year already rewarded ('. $userHonor->user->login .').');
				}
			} else {
				$this->error(' -- Best Respondent of the '. $lastMonth->format('M Y') .' year not rewarded (reward not found).');
			}
		} else {
			$this->info('Best Respondent of the '. $lastMonth->format('M Y') .' year not found.');
		}

		if($bestCommentator->first()) {
			$user = $bestCommentator->first();
			$honor = Honor::whereKey('bestCommentatorOfMonth')->first();

			if($honor) {
				$this->info('Best Commentator: ' . $user->login);

				$data = array(
					'user_id' => $user->id,
					'honor_id' => $honor->id,
					'comment' => 'Лучший комментатор за '.
						mb_strtolower(DateHelper::$monthsList[$lastMonth->format('n')]) . ' ' .
						$lastMonth->format('Y') .' года.'
				);

				$userHonor = UserHonor::whereHonorId($honor->id)
					->where('created_at', '>=', \Carbon\Carbon::now()->subMonth())
					->first();

				if (!$userHonor) {
					if(UserHonor::create($data)){
						$this->info(' -- Best Commentator ('. $user->login .') of the '. $lastMonth->format('M Y') .' year was rewarded.');
					}
				} else {
					$this->info(' -- Best Commentator of the '. $lastMonth->format('M Y') .' year already rewarded ('. $userHonor->user->login .').');
				}
			} else {
				$this->error(' -- Best Commentator of the '. $lastMonth->format('M Y') .' year not rewarded (reward not found).');
			}
		} else {
			$this->info('Best Commentator of the '. $lastMonth->format('M Y') .' year not found.');
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
