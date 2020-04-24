<?php
namespace App\Http\Controllers\Api\V1;
use App\User;
use Illuminate\Support\Str;

trait UpdateApiToken
{
    private function updateApiToken(User $user)
    {
        $user->api_token = Str::random(80);
        $user->save();
    }
}
