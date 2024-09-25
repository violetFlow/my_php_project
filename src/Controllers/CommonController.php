<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\CommonService;
use App\Validators\CommonValidator;

class CommonController
{
    private $commonService;

    // コンストラクタでCommonServiceを注入
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function hello(Request $request, Response $response, array $args): Response
    {
        $name = $args['name'];
        $errors = CommonValidator::helloValidate($name);

        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $helloMsg = $this->commonService->getHelloMsg($name);

        $response->getBody()->write(json_encode(['hello_message' => $helloMsg]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function encrypt(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $errors = CommonValidator::encryptValidate($data['encryptTxt']);

        // Check validation result
        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $encryptedTxt = $this->commonService->encrypt($data['encryptTxt']);

        $response->getBody()->write(json_encode(['encryptedTxt' => $encryptedTxt]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function decrypt(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $errors = CommonValidator::decryptValidate($data['decryptTxt']);

        // Check validation result
        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $decryptedTxt = $this->commonService->decrypt($data['decryptTxt']);

        $response->getBody()->write(json_encode(['decryptedTxt' => $decryptedTxt]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
