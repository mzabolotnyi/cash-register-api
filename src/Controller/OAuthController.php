<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use OAuth2\OAuth2;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Rest\Route("oauth")
 * @SWG\Tag(name="User / Authorization")
 */
class OAuthController extends SuperController
{
    /**
     * @Route("/token", methods={"POST"}, name="app_get_token")
     *
     * @SWG\Post(summary="Authorize user",
     *
    description="
    |-------------------------------------------------------------------------------------------------------------|
    |                                              Refresh token                                                  |
    |-------------------------------------------------------------------------------------------------------------|
    |{                                                                                                            |
    |   'client_id': '1_40k0j9ocz3msk0044sgok0o0k8g4s8s4gowoos4kws8co04c08',                                      |
    |   'client_secret': '3cy6qxflr4g0wg4wkg40sskcgo0kkc4ks84c4g84wokg8ggckc',                                    |
    |   'grant_type': 'refresh_token',                                                                            |
    |   'refresh_token': 'YWQxMzI0MjVkMDc4YTA3NGNmM2ExNDgyZTY0MjkxMzVhZTk4MWI1OGQ1MjM0ZDI0MWM3YWZmOTQ2MWFiMjhiYQ' |
    |}                                                                                                            |
    |-------------------------------------------------------------------------------------------------------------|
    |                                              User credentials                                               |
    |-------------------------------------------------------------------------------------------------------------|
    |{                                                                                                            |
    |   'client_id': '1_40k0j9ocz3msk0044sgok0o0k8g4s8s4gowoos4kws8co04c08',                                      |
    |   'client_secret': '3cy6qxflr4g0wg4wkg40sskcgo0kkc4ks84c4g84wokg8ggckc',                                    |
    |   'grant_type': 'password',                                                                                 |
    |   'username': 'username',                                                                              |
    |   'password': 'fakePsw123',                                                                                 |
    |}                                                                                                            |
    |-------------------------------------------------------------------------------------------------------------|
    ",
     *
     *      @SWG\Response(
     *          response=200,
     *          description="OK",
     *          @SWG\Schema(
     *              type="object",
     *              	@SWG\Property(property="access_token", type="string"),
     *              	@SWG\Property(property="expires_in", type="string"),
     *              	@SWG\Property(property="token_type", type="string"),
     *              	@SWG\Property(property="scope", type="string"),
     *              	@SWG\Property(property="refresh_token", type="string"),
     *          )
     *      ),
     *      @SWG\Response(
     *          response=400,
     *          description="Invalid request"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          type="string",
     *          required=true,
     *          @SWG\Definition(
     *              required={"client_id", "client_secret", "grant_type", "username", "password"},
     *              @SWG\Property(property="client_id", type="string"),
     *              @SWG\Property(property="client_secret", type="string"),
     *              @SWG\Property(property="grant_type", type="string"),
     *              @SWG\Property(property="username", type="string", description="Username (required for 'password' grant type)"),
     *              @SWG\Property(property="password", type="string", description="User password (required for 'password' grant type)"),
     *              @SWG\Property(property="refresh_token", type="string", description="Refresh token (required for 'refresh_token' grant type)")
     *          )
     *      ),
     * )
     * @param Request $request
     * @param OAuth2 $server
     * @return Response
     */
    public function token(Request $request, OAuth2 $server): Response
    {
        return $server->grantAccessToken($request);
    }
}