<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;


class AdminController extends Controller
{
    /**
     * Tampilkan halaman admin index.
     */
    public function index()
    {
        return view('admin.index');
    }

    public function antrian()
    {
        return view('admin.antrian');
    }







}

