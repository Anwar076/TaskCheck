<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\SubmissionController as ApiSubmissionController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index(Request $request, ApiSubmissionController $apiSubmissions)
    {
        $initialSubmissions = $apiSubmissions
            ->index($request)
            ->getData(true);

        return view('admin.submissions.index-api', compact('initialSubmissions'));
    }
}
