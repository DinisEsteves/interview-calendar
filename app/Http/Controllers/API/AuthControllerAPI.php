<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthControllerAPI extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::attempt($request->all())) {
            return response()->json(
                [
                    'error' => 'Invalid credentials',
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $accessToken = $request->user()->createToken(Str::random());

        return response()->json([
            'access_token' => $accessToken->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($accessToken->token->expires_at)->toDateTimeString(),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterRequest $request)
    {
        try{
            
            $user = User::create([  
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
            ]);
        }catch(\Exception $ex){
            return response()->json(
                [
                    'error' => $ex->getMessage(),
                    'status' => Response::HTTP_I_AM_A_TEAPOT
                ],
                Response::HTTP_I_AM_A_TEAPOT
            );
        }
        return response()->json(['attributes' => $user, 'status' => Response::HTTP_OK], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
