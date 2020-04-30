<?php
/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="內部遊戲化成就系統",
 *      description="給內部同仁使用的遊戲化成就系統"
 * )
 */

/**
 *  @OA\Server(
 *      url="http://127.0.0.1",
 *      description="本機伺服器"
 *  )
 */

 /**
 * @OAS\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT"
 *  )
 **/

/**
 * @OA\ExternalDocumentation(
 *     description="Github 連結",
 *     url="https://github.com/balloon-chen-104/inner-gamification-achievement-system"
 * )
 * 
 * @OA\Tag(
 *     name="Flash Message",
 *     description="新增快訊"
 * )
 *
 */
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
