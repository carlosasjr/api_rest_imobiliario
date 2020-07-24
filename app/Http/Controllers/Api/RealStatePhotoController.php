<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\RealStatePhoto;
use Illuminate\Support\Facades\Storage;

class RealStatePhotoController extends Controller
{
    /**
     * @var RealStatePhoto
     */
    private $realStatePhoto;

    public function __construct(RealStatePhoto $realStatePhoto)
    {
        $this->realStatePhoto = $realStatePhoto;
    }

    public function setThumb($realStateId, $photoId)
    {
        try {
            $photo = $this->realStatePhoto
                ->where('real_state_id', $realStateId)
                ->where('is_thumb', true);

            if ($photo->count() > 0) {
                $photo->first()->update(['is_thumb' => false]);
            }

            $photo = $this->realStatePhoto->find($photoId);
            $photo->is_thumb = true;
            $photo->save();

            return response()->json([
                'data' => [
                    'mensagem' => 'Thumb atualizada com sucesso!'
                ]
            ],200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function remove($photoId)
    {
        try {
            $photo = $this->realStatePhoto->find($photoId);

            if ($photo->is_thumb) {
                $message = new ApiMessages('Não é possível remover foto de thumb.');
                return response()->json($message->getMessage(), 401);
            }

            if ($photo) {
                Storage::disk('public')->delete($photo->photo);
                $photo->delete();
            }

            return response()->json([
                'data' => [
                    'mensagem' => 'Foto removida com sucesso!'
                ]
            ],200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

}
