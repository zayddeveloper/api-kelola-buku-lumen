<?php


namespace App\Helper;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CustomController extends Controller
{
    /** @var Request $request */
    protected $request;

    /**
     * CustomController constructor.
     */
    public function __construct()
    {
        $this->request = Request::createFromGlobals();
    }

    public function postField($key)
    {
        return $this->request->request->get($key);
    }

    public function parseRequestBody()
    {
        return $this->request->all();
    }

    public function jsonResponse($msg = '', $status = 200, $data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $msg,
            'data' => $data
        ], $status);
    }
}
