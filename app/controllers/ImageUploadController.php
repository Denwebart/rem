<?php

class ImageUploadController extends BaseController
{
    /**
     * Загрузка временного изображения
     *
     * @return \Illuminate\Http\JsonResponse
     */
	public function uploadIntoTemp()
	{
		if(Request::ajax()) {

			$data = Input::all();

			$rules = [
				'image' => 'image|max:15000|required_without_all:avatar',
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

            $class = Request::has('class') ? Request::get('class') : ' page-image';
			return Response::json(array(
				'success' => true,
				'imageUrl' => $imageUrl,
                'imageHtml' => (string) View::make('cabinet::user._pageImage', ['imageUrl' => $imageUrl, 'class' => $class])->render(),
			));
		}
	}

    /**
     * Удаление временного изображения
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFromTemp()
    {
        if(Request::ajax())
        {
            $image = Input::get('imageName');
            $tempPath = Input::get('tempPath');

            // delete old image with directory
            if(File::exists(public_path() . $tempPath . $image)) {
                File::delete(public_path() . $tempPath . $image);
            }

            return Response::json([
                'success' => true,
//                'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Изображение удалено.'])
            ]);
        }
    }
}