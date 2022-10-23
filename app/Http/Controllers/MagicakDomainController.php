<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Coding for multi domains (not finished)
class MagicakDomainController extends Controller
{
    public function index(Request $request)
    {
        $origin = array("mydomain.com", "mydomain2.com");
        $domain = parse_url(request()->root())['host'];

        if (in_array($domain, $origin)) {
            if ($domain === 'mydomain.com') {
                return view('myview');
        }
            if ($domain === 'mydomain2.com') {
                return view('myview2');
        }
        } else{
            return view('unauthorized');
        }
        return view('application');
    }
}
