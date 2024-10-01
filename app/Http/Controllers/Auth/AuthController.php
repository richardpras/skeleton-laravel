<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\User;
use App\Http\Resources\Auth\AuthResource;
use App\Http\Resources\Auth\UserResource;
use Illuminate\Support\Facades\Validator;
// use Laravel\Passport\Token;
use Lcobucci\JWT\Configuration;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;

class AuthController extends Controller
{
  /**
  * Create user
  *
  * @param  [string] name
  * @param  [string] email
  * @param  [string] password
  * @param  [string] password_confirmation
  * @return [string] message
  */
  public function register(RegisterRequest $request)
  {
    $validator = Validator::make($request->all(),$request->rules(),$request->messages());
    
    if($validator->fails()){
      return $this->sendError('Validation Failed',$validator->errors());
    }
    $user = new User([
      'username' => $request->username,
      'email' => $request->email,
      'password' => bcrypt($request->password)
    ]);
    if($user->save()){
      $accessToken = $user->createToken('auth_token')->accessToken;
      return $this->sendResponse(['accessToken'=>$accessToken], 'created user.');
    }else{
      return $this->sendError(['error'=>'Provide proper details'], 'Provide proper details.');
    }
  }

  public function login(LoginRequest $request)
  {
    $validator = Validator::make($request->all(),$request->rules(),$request->messages());
    if($validator->fails()){
      return $this->sendError('Validation Failed',$validator->errors());
    }
    if(!$request->authenticate()){
      return $this->sendError('Unauthorized.',["Username and Password not match"]);
    }else{
        $user = Auth::user();
        $token = $user->createToken('auth_token');
        if ($request->remember_me){
            return [
              'expires_at'=> date('Y-m-d H:i:s',strtotime($token->token->expires_at)),
              'accessToken' =>$token->accessToken 
            ];
        }
        return [
          'accessToken' =>$token->accessToken
        ];
    }
   
  }
  
  public function user(Request $request)
  {
    $model=User::where('id',Auth::id())->first();
    return $this->sendResponse(new AuthResource($model), 'Details user.');
  }

  public function logout(Request $request)
  {
    $bearerToken = request()->bearerToken();
    $tokenId = Configuration::forUnsecuredSigner()->parser()->parse($bearerToken)->claims()->get('jti');
    // $client = Token::find($tokenId)->client;
    $tokenRepository = app(TokenRepository::class);
    $refreshTokenRepository = app(RefreshTokenRepository::class);
    
    // Revoke an access token...
    $tokenRepository->revokeAccessToken($tokenId);
    
    // Revoke all of the token's refresh tokens...
    $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);

    return $this->sendResponse(['message' => 'Successfully logged out'], 'logout user.');
  }
}
