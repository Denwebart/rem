<?php

class AdminController extends \BaseController
{
	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	public function index()
	{
		return View::make('admin::index')->with(
				'cacheSize', $this->getDirectorySize(Config::get('cache.path'))
			);
	}

	public function clearCache()
	{
		if(Request::ajax()) {
			Cache::flush();
			return Response::json(array(
				'success' => true,
				'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Кэш очищен.']),
			));
		} else {
			Cache::flush();
			return Redirect::back()->with('successMessage', 'Кэш очищен.');
		}
	}

	protected function getDirectorySize($directory)
	{
		if(File::isFile($directory)) {
			return [
				'filesSize' => filesize($directory),
				'filesCount' => 0
			];
		}
		if($dh = opendir($directory)) {
			$size = 0;
			$n = 0;
			while(($file = readdir($dh)) !== false) {
				if($file == '.' || $file == '..') continue;
				$n++;
				$data = $this->getDirectorySize($directory . '/' . $file);
				$size += $data['filesSize'];
				$n += $data['filesCount'];
			}
			closedir($dh);
			return ['filesSize' => $size, 'filesCount' => $n];
		}
		return ['filesSize' => 0, 'filesCount' => 0];
	}
}