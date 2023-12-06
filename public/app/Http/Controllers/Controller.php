<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Web2 Api Documentation",
 *     description="Web2 Api Documentation",
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * ),
* @OA\SecurityScheme(
*  type="http",
*  description="Acess token obtido na autenticação",
*  name="Authorization",
*  in="header",
*  scheme="bearer",
*  bearerFormat="JWT",
*  securityScheme="bearerToken"
* )
*/
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
