<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserForm;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use GuzzleHttp\Client;

class UserController extends Controller
{
    /**
     * Handle the incoming request.
     * @throws NotFoundException
     * @throws ValidationErrorException
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->only('page','count'), [
            'page' => 'required|min:1|integer',
            'count' => 'required|min:1|integer'
        ]);

        if ($validator->fails()) {
            throw new ValidationErrorException($validator->errors(), 'Validation Failed', 422);
        }

        $page = $request->input('page');
        $count = $request->input('count');

        $users = User::paginate($count, ['*'], page: $page);

        if ($users->lastPage() < $page) {
            throw new NotFoundException('Page not found',404);
        }

        return new UserCollection($users);
    }

    /**
     * @throws ValidationErrorException
     */
    public function getUser($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            throw new ValidationErrorException($validator->errors(), 'Validation Failed', 422);
        }
        $user = User::find($id);

        if(!$user){
            throw new NotFoundException('User not found', 404);
        }
        return new UserResource($user);
    }

    public function register(RegisterUserForm $request)
    {
        $data = $request->validated();
        $token = $request->bearerToken();

        if(!Cache::has($token)){
            return response()->json(['success' => 'false', 'message' => 'The token expired'],401);
        }

        $photo = $data['photo'];

        $path = Storage::disk('public')->put('photos', $photo);
//        Log::info($path);

        $compressedImage = $this->tinyPngCompressingImage($path);

        $data['photo'] = $compressedImage;

        $user = new User($data);
        $user->save();

        Cache::forget($token);

        return response()->json(['success' => true, 'user_id' => $user->id, 'message' => 'New user successfully registered'], 201);
    }

    public static function tinyPngCompressingImage($path)
    {
        try {
            $tinyPngApiKey = config('services.tinypng.api_key');
            $fileContents = Storage::disk('public')->get($path);

            $client = new Client([
                'base_uri' => 'https://api.tinify.com',
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode('api:' . $tinyPngApiKey)
                ]
            ]);
            $response = $client->post('/shrink',[
                'body' => $fileContents,
                'headers' => [
                    'Content-Type' => 'application/octet-stream'
                ],
            ]);

            if ($response->getStatusCode() === 201) {
                $responseData = json_decode($response->getBody(), true);
                $compressedImageUrl = $responseData['output']['url'];

                $resizeResponse = $client->post($compressedImageUrl, [
                    'json' => [
                        'resize' => [
                            'method' => 'cover',
                            'width' => 70,
                            'height' => 70
                        ]
                    ]
                ]);

                if ($resizeResponse->getStatusCode() === 200) {
                    $resizedImageContent = $resizeResponse->getBody()->getContents();
                    Storage::disk('public')->put($path, $resizedImageContent);
                    return $path;
                } else {
//                    Log::error('TinyPNG Resize error: ' . $resizeResponse->getBody());
                    return null;
                }
            }else {
//                Log::error('TinyPNG API error: ' . $response->getBody());
                return null;
            }
        }
        catch (\Exception $e) {
//            Log::error('Error compressing image: ' . $e->getMessage());
            return null;
        }
    }
    public function generateToken()
    {
        $token = (string) Str::uuid();
        Cache::set($token, true,  env('TOKEN_EXPIRATION'));

        return response()->json($token, 200);
    }
}
