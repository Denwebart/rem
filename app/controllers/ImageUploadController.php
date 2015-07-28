<?php

class ImageUploadController extends BaseController
{
	public function postImageUpload($pageId)
	{
		$input = Input::all();

		$rules = [
			'upload' => 'image|max:15000|required',
		];

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
			return Response::make($validation->messages(), 400);
		}

		$file = Input::file('upload');

		dd($file->getClientOriginalName());

//		$pageId->gallery->addPhoto($file); //saving file to server and path to DB
//		return URL::to($pageId->gallery->path.'/'.$file->getClientOriginalName());
	}
}