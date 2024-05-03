<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Api\ApiMessages;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function index(){
        try {
            $user = $this->user->paginate(10);
            return response()->json($user, 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage(), $th->getCode());
            return response()->json($message->getMessage(),404);
        }
    }

    public function show($id){
        try {
            $user = User::with('profile')->findOrFail($id);
            $user->profile->social_network = unserialize($user->profile->social_network);
            return response()->json($user, 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage(), $th->getCode());
            return response()->json($message->getMessage(),404);
        }
    }

    public function store(UserRequest $request){
        $data = $request->all();

        Validator::make($data,[
            'phone' => 'required',
            'mobile_phone' => 'required'
        ])->validate();

        try {

            $data['password'] = bcrypt($data['password']);

            if(!$request->has('password') || !$request->get('password')){
                $message = new ApiMessages('É necessário informar uma senha!', 401);
                return response()->json($message->getMessage(), 401);
            }
            $user = User::create($data);
            $user->profile()->create(
                [
                    'phone' => $data['phone'],
                    'mobile_phone' => $data['mobile_phone']
                ]
            );
            return response()->json(['msg', 'Usuário cadastrado com sucesso!'], 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage(), $th->getCode());
            return response()->json($message->getMessage(),500);
        }
    }

    public function update($id, UserRequest $request){

        $data = $request->all();
        
        if($request->has('password' && $request->get('password')))
                $data['password'] = bcrypt($data['password']);
            else
                unset($data['password']);

        Validator::make($data,[
            'profile.phone' => 'required',
            'profile.mobile_phone' => 'required'
        ])->validate();

        try {
            $profile = $data['profile'];
            $profile['social_network'] = serialize($profile['social_network']);
            // dd($profile);
            $user = User::findOrFail($id);
            $user->update($data);
            $user->profile()->update($profile);
            return response()->json(['msg', 'Usuário atualizado com sucesso!'], 200);

        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage(), $th->getCode());
            return response()->json($message->getMessage(),500);
        }
    }

    public function destroy($id){
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['msg', 'Usuário deletado com sucesso!'
        ], 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage(), $th->getCode());
            return response()->json($message->getMessage(),500);
        }
    }


}

