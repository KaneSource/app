<?php
namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use think\response\Json;
use Throwable;

/**
 */
class ExceptionHandle extends Handle
{
    /**
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        if ( $e instanceof  HttpException) {
            Json::create([
                'code' => $e->getStatusCode(),
                'msg' => $e->getMessage(),
                'data' => '',
                'count' => 0
            ],'json')->send();exit;
        }
        if ($e instanceof ValidateException) {
            Json::create([
                'code' => '1004',
                'msg' => $e->getMessage(),
                'data' => '',
                'count' => 0
            ],'json')->send();exit;
        }

        return parent::render($request, $e);
    }
}
