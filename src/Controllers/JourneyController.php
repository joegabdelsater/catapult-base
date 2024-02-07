<?php
namespace Joegabdelsater\CatapultBase\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;


class JourneyController extends BaseController
{
    public function index()
    {   
        return view('catapult::welcome');
    }
}