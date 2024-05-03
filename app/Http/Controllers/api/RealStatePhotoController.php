<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\RealStatePhoto;
use App\Api\ApiMessages;
use Illuminate\Support\Facades\Storage;

class RealStatePhotoController extends Controller
{
    private $realStatePhoto;

    public function __construct(RealStatePhoto $realStatePhoto){
        $this->realStatePhoto = $realStatePhoto;
    }

    public function isThumb($photoId, $realStateId){
        try {
            $photo = $this->realStatePhoto->where('real_state_id', $realStateId)->first()
                          ->where('is_thumb', true);
            if($photo->count()) $photo->first()->update(['is_thumb' => false]);
               
            $photo = $this->realStatePhoto->where('real_state_id', $realStateId)
            ->find($photoId);
            $photo->update(['is_thumb'=>true]);
            
            return response([
                'msg' => 'Thumb atualizada com sucesso!' 
            ], 200);

        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage(), $th->getCode());
            return response()->json($message->getMessage(), 401);
        }
    }
    public function remove($photoId){
        try{
            $photo = $this->realStatePhoto->find($photoId);
            if($photo->is_thumb){
                $message = new ApiMessages('Não é possível remover foto da Thumb!', 401);
                return response()->json($message->getMessage(), 401);
            }
            if($photo !== null){
                Storage::disk('public')->delete($photo->photo);
                $photo->delete();
                return response([
                    'msg' => 'Foto removida com sucesso!' 
                ], 200);
            } else {
                return response([
                    'msg' => 'Foto não encontrada.' 
                ], 404);
            }

        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage(), $th->getCode());
            return response()->json($message->getMessage(), 401);
        }
    }
}
