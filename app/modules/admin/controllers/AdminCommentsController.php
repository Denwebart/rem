<?php

class AdminCommentsController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}

		$this->beforeFilter(function()
		{
			if(Auth::user()->isModerator()) {
				return Response::view('admin::errors.403', [], 403);
			}
		}, ['only' => ['destroy']]);
	}

	/**
	 * Display a listing of comments
	 *
	 * @return Response
	 */
	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			$comments = Comment::orderBy($sortBy, $direction)->with('page.parent.parent', 'user')->paginate(10);
		} else {
			$comments = Comment::orderBy('created_at', 'DESC')->with('page.parent.parent', 'user')->paginate(10);
		}

		return View::make('admin::comments.index', compact('comments'));
	}

	/**
	 * Display the specified comment.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$comment = Comment::findOrFail($id);

		return View::make('admin::comments.show', compact('comment'));
	}

	/**
	 * Show the form for editing the specified comment.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$comment = Comment::find($id);

		return View::make('admin::comments.edit', compact('comment'));
	}

	/**
	 * Update the specified comment in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$comment = Comment::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Comment::$rulesForUpdate);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$comment->update($data);

		return Redirect::route('admin.comments.index');
	}

	/**
	 * Remove the specified comment from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$comment = Comment::find($id);
		if($comment->is_answer) {
			$comment->user->removePoints(User::POINTS_FOR_ANSWER);
			if($comment->mark == Comment::MARK_BEST) {
				$comment->user->removePoints(User::POINTS_FOR_BEST_ANSWER);
				$comment->user->setNotification(Notification::TYPE_POINTS_FOR_BEST_ANSWER_REMOVED, [
					'[answer]' => $comment->comment,
					'[pageTitle]' => $comment->page->getTitle(),
					'[linkToPage]' => URL::to($comment->page->getUrl())
				]);
			} else {
				$comment->user->setNotification(Notification::TYPE_ANSWER_DELETED, [
					'[answer]' => $comment->comment,
					'[pageTitle]' => $comment->page->getTitle(),
					'[linkToPage]' => URL::to($comment->page->getUrl())
				]);
			}
		} else {
			$comment->user->removePoints(User::POINTS_FOR_COMMENT);
			$comment->user->setNotification(Notification::TYPE_COMMENT_DELETED, [
				'[comment]' => $comment->comment,
				'[pageTitle]' => $comment->page->getTitle(),
				'[linkToPage]' => URL::to($comment->page->getUrl())
			]);
		}
		$comment->delete();

		return Redirect::route('admin.comments.index');
	}

}
