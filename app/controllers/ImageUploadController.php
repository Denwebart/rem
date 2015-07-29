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

		$fileName = TranslitHelper::generateFileName($file->getClientOriginalName());

		$imagePath = public_path() . '/uploads/' . (new Page)->getTable() . '/' . $pageId . '/';
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

		if($image->width() > 225) {
			$image->insert(public_path($watermark), 'center')
				->save($imagePath . 'origin_' . $fileName)
				->resize(225, null, function ($constraint) {
					$constraint->aspectRatio();
				})
				->save($imagePath . $fileName);
		} else {
			$image->insert(public_path($watermark))
				->save($imagePath . $fileName);
		}
		$cropSize = ($image->width() < $image->height()) ? $image->width() : $image->height();

		$image->crop($cropSize, $cropSize)
			->resize(50, null, function ($constraint) {
				$constraint->aspectRatio();
			})->save($imagePath . 'mini_' . $fileName);

		return public_path() . '/uploads/' . (new Page)->getTable() . '/' . $pageId . '/' . $fileName;

//		dd($file->getClientOriginalName());

//		$pageId->gallery->addPhoto($file); //saving file to server and path to DB
//		return URL::to($pageId->gallery->path.'/'.$file->getClientOriginalName());
	}
}

//<script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
//    <script type="text/javascript">
////        CKEDITOR.replaceAll('editor');
//        var csrf = '{{csrf_token()}}' ;
//
//        CKEDITOR.replace('content', {
//            filebrowserUploadUrl: '{{URL::action("ImageUploadController@postImageUpload", $page->id)}}?_token='+csrf
//        });
//    </script>