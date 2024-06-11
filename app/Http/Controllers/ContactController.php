<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller

{
    
    public function index()
    {
        return view('contact.index');

    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ]);

        // Ambil data dari formulir
        $name = $request->input('name');
        $email = $request->input('email');
        $subject = $request->input('subject');
        $message = $request->input('message');

        // Lakukan sesuatu dengan data (misalnya, kirim email, simpan ke database, dll.)
        

        return back()->with('success', 'Terima kasih telah menghubungi kami!');
    }



}
