<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function flashMsg($class, $message)
    {
        Session::flash('message', $message);
        Session::flash('alert-class', 'alert-'.$class);
    }

    public function setMiddleware()
    {
        if (\Request::Ajax() && !\Request::has('fromWebApp'))
            $middleware = 'jwt.auth';
        else
            $middleware = 'auth';

        return $middleware;
    }

    public function makeResponse($view, $objects = [])
    {
        if (\Request::ajax())
        {
            return \Response::json($objects);
        }

        return View($view, $objects);
    }
}
