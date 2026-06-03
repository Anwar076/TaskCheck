<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Mobile\Concerns\RespondsWithJson;

abstract class MobileController extends Controller
{
    use RespondsWithJson;
}
