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

			if($image->width() < 300) {
				$watermark = 'images/watermark-300.png';
			} elseif($image->width() < 500) {
				$watermark = 'images/watermark-500.png';
			} elseif($image->width() > 1000) {
				$watermark = 'images/watermark-1000.png';
			} elseif($image->width() > 1500) {
				$watermark = 'images/watermark-1500.png';
			} else {
				$watermark = 'images/watermark.png';
			}

			$image->insert(public_path($watermark), 'center')
				->save($imagePath . $fileName);

			$imageUrl = URL::to(urldecode($path) . $fileName);

			return Response::json(array(
				'success' => true,
				'imageUrl' => $imageUrl,
				'imageName' => $fileName,
			));
		}
	}
}