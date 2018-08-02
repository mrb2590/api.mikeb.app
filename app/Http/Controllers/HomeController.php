<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show the application homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Show the application dashboard.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request, $slug)
    {
        if ($slug == 'dashboard') {
            return redirect()->route('dashboard', ['slug' => $request->user()->slug]);
        }

        if ($slug != $request->user()->slug) {
            abort(404);
        }

        return view('dashboard');
    }
}
