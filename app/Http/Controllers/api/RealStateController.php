<?php

namespace App\Http\Controllers\api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;
use App\Models\RealState;

class RealStateController extends Controller
{
    private $realState;

    public function __construct(RealState $realState){
        $this->realState = $realState;
    }

    public function index(){
        try{
            // $realState = $this->realState->paginate('10');
            // dd(auth()->user());
            $realState = auth()->user()->property();
            return response()->json($realState->paginate(10), 200);
        } catch(\Exception $e){
            $message = new ApiMessages($e->getMessage(), $e->getCode());
            return response()->json($message->getMessage(), 404);
        }
    }
    

    public function show($id){
        try{
            $realState = auth()->user()->property()->with('photos')->findOrFail($id);
            return response()->json($realState,200);
        } catch(\Exception $e){
            $message = new ApiMessages($e->getMessage(), $e->getCode());
            return response()->json($message->getMessage(), 404);
        }
    }

    public function store(RealStateRequest $request){

        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $images = $request->file('images');

        try {

            $realState = RealState::create($data);

            if(isset($data['categories']) && count($data['categories'])){
                $realState->categories()->sync($data['categories']);
            }

            if($images){
                $imagesUploaded = [] ;
                foreach($images as $image){
                    $path = $image->store('images', 'public');
                    $imagesUploaded[] = ['photo' => $path, 'is_thumb' => false];
                }
                $realState->photos()->createMany($imagesUploaded);
            }

            return response()->json([
                'msg', 'Imóvel cadastrado com sucesso!'
            ], 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage(), $th->getCode());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function update($id, RealStateRequest $request){
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $images = $request->file('images');


        try {
            $realState = auth()->user()->property()->findOrFail($id);
            $realState->update($data);
            if(isset($data['categories']) && count($data['categories'])){
                $realState->categories()->sync($data['categories']);
            }

            if($images){
                $imagesUploaded = [] ;
                foreach($images as $image){
                    $path = $image->store('images', 'public');
                    $imagesUploaded[] = ['photo' => $path, 'is_thumb' => false];
                }
                $realState->photos()->createMany($imagesUploaded);
            }

            return response()->json([
                'msg', 'Imóvel atualizado com sucesso!'
            ]);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage(), $th->getCode());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy($id){
        try {
            $realState = auth()->user()->property()->findOrFail($id);
            $realState->delete();
            return response()->json([ 'msg', 'Imóvel deletado com sucesso']);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage(), $th->getCode());
            return response()->json($message->getMessage(), 404);
        }

    }   

}