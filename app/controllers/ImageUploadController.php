<?php

class ImageUploadController extends BaseController
{
	public function postImageUpload($path)
	{
		if(Request::ajax()) {

			$data = Input::all();

			$rules = [
				'image' => 'image|max:15000|required',
			];

			$validation = Validator::make($data, $rules);

			if ($validation->fails())
			{
				return Response::json(array(
					'fail' => true,
					'errors' => $validation->getMessageBag()->toArray(),
				));
			}

			$file = $data['image'];

			$fileName = TranslitHelper::generateFileName($file->getClientOriginalName());

			$imagePath = public_path() . urldecode($path);
			$image = Image::make($file->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath, 0755, true);

			$watermark = Image::make(public_path('images/watermark.png'));
			$watermark->resize(($image->width() * 2) / 3, null, function ($constraint) {
					$constraint->aspectRatio();
				})->save($imagePath . 'watermark.png');

			$image->insert($imagePath . 'watermark.png', 'center')
				->save($imagePath . $fileName);

			$imageUrl = URL::to(urldecode($path) . $fileName);

			return Response::json(array(
				'success' => true,
				'imageUrl' => $imageUrl,
				'imageName' => $fileName,
			));
		}
	}

	public function uploadIntoTemp()
	{
		if(Request::ajax()) {

			$data = Input::all();

			$rules = [
				'image' => 'image|max:15000|required',
			];

			$validation = Validator::make($data, $rules);

			if ($validation->fails())
			{
				return Response::json(array(
					'fail' => true,
					'errors' => $validation->getMessageBag()->toArray(),
				));
			}

			$file = $data['image'];

			$fileName = TranslitHelper::generateFileName($file->getClientOriginalName());

			$tempPath = Input::get('tempPath');

			$imagePath = public_path() . $tempPath;
			$image = Image::make($file->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath, 0755, true);

			$watermark = Image::make(public_path('images/watermark.png'));
			$watermark->resize(($image->width() * 2) / 3, null, function ($constraint) {
				$constraint->aspectRatio();
			})->save($imagePath . 'watermark.png');

			$image->insert($imagePath . 'watermark.png', 'center')
				->save($imagePath . $fileName);

			$imageUrl = $tempPath . $fileName;

			if(File::exists($imagePath . 'watermark.png')) {
				File::delete($imagePath . 'watermark.png');
			}

			return Response::json(array(
				'success' => true,
				'imageUrl' => $imageUrl,
				'imageName' => $fileName,
				'tempPath' => $tempPath,
			));
		}
	}
}