<?php

class CommentsController extends BaseController
{
	public function addComment($id)
	{
		if(Request::ajax()) {
			$inputData = Input::get('formData');
			parse_str($inputData, $formFields);

			$userData = array(
				'page_id' => $id,
				'parent_id' => $formFields['parent_id'],
				'user_id' => Auth::user()->id,
				'comment' => $formFields['comment'],
			);

			$validator = Validator::make($userData, Comment::$rules);

			if ($validator->fails())
				return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
				));
			else {
				//save to DB user details
				if (Comment::create($userData)) {
					//return success message
					return Response::json(array(
						'success' => true,
					));
				}
			}
		}
	}

}