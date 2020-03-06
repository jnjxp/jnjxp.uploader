<?php

declare(strict_types=1);

namespace Jnjxp\Uploader\Handler;

use Fig\Http\Message\StatusCodeInterface as Code;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class UploadResponder implements UploadResponderInterface
{
    protected $responseFactory;

    protected $url;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        callable $urlHelper = null
    ) {
        $this->responseFactory = $responseFactory;
        $this->url = $urlHelper;
    }

    public function invalidRequest(ServerRequestInterface $request) : ResponseInterface
    {
        $request;
        return $this->fail(Code::STATUS_METHOD_NOT_ALLOWED);
    }

    public function noFile() : ResponseInterface
    {
        return $this->fail(Code::STATUS_BAD_REQUEST, 'no file uploaded');
    }

    public function invalidFile(UploadedFileInterface $file) : ResponseInterface
    {
        switch ($file->getError()) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $status = Code::STATUS_PAYLOAD_TOO_LARGE;
                $message = 'File too large';
                break;
            case UPLOAD_ERR_PARTIAL:
            case UPLOAD_ERR_NO_FILE:
                $status = Code::STATUS_CONFLICT;
                $message = 'Partial or no file';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
            case UPLOAD_ERR_CANT_WRITE:
                $status = Code::STATUS_INTERNAL_SERVER_ERROR;
                $message = 'Could not store file';
                break;
            default:
                $status = Code::STATUS_BAD_REQUEST;
                $message = 'Upload failed';
                break;
        }
        return $this->fail($status, $message);
    }

    public function success(string $fileid) : ResponseInterface
    {
        return $this->jsonResponse([
            'success' => true,
            'fileid'  => $fileid,
            'file'    => $this->url ? ($this->url)('uploader.get', ['fileid' => $fileid]) : null
        ]);
    }

    protected function fail(
        int $status = Code::STATUS_BAD_REQUEST,
        string $message = 'upload failed'
    ) : ResponseInterface {
        return $this->jsonResponse(['success' => false, 'message' => $message], $status);
    }

    protected function jsonResponse(array $data = [], int $status = Code::STATUS_OK) : ResponseInterface
    {
        $response = $this->responseFactory
            ->createResponse($status)
            ->withHeader('Content-Type', 'application/json');

        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
