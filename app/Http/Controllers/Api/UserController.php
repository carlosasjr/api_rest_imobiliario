<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $users = $this->user->paginate(10);

        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();

        if (!$request->has('password') || !$request->get('password')) {
            $message = new ApiMessages('Campo Senha é de preenchimento obrigatório');
            return response()->json($message->getMessage(), 401);
        }

        Validator::make($data, [
            'profile.phone' => 'required',
            'profile.mobile_phone' => 'required',
        ])->validate();


        try {

            $data['password'] = bcrypt($data['password']);

            $user = $this->user->create($data);

            if ($request->has('profile')) {
                $profile = $data['profile'];

                $profile['social_networks'] = (isset($profile['social_networks'])) ? serialize($profile['social_networks']) : null;

                $user->profile()->create($profile);
            }

            return response()->json([
                'data' => [
                    'mensagem' => 'Usuário cadastrado com sucesso!'
                ]
            ]);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {

            $user = $this->user->with('profile')->findOrFail($id);

            $user->profile->social_networks = unserialize($user->profile->social_networks);

            return response()->json([
                'data' => $user
            ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UserRequest $request, $id)
    {
        $data = $request->all();

        if ($request->has('password') && $request->get('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }


        Validator::make($data, [
            'profile.phone' => 'required',
            'profile.mobile_phone' => 'required',
        ])->validate();

        try {

            $user = $this->user->findOrFail($id);

            $user->update($data);

            if ($request->has('profile')) {
                $profile = $data['profile'];

                $profile['social_networks'] = (isset($profile['social_networks'])) ? serialize($profile['social_networks']) : null;

                $user->profile()->update($profile);
            }


            return response()->json([
                    'mensagem' => 'Usuário atualizado com sucesso!'
                ]
                , 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {

            $user = $this->user->findOrFail($id);

            $user->delete();

            return response()->json([
                    'msg' => 'Usuário removido com sucesso!'
                ]
                , 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());

            return response()->json($message->getMessage(), 401);
        }
    }
}
