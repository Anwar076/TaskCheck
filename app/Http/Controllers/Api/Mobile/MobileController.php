<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Api\Mobile\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;

abstract class MobileController extends Controller
{
    use RespondsWithJson;
}
