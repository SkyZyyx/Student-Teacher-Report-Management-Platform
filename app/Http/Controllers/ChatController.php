<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ChatController extends Controller
{
   public function chat($id){
    return view('chat', compact('id')); 
   }

   public function index(){
      $users = User::all(); // Assuming you want to get the first user or change this as per your requirement
        return view('indexchat', compact('users'));
      
   }
   
}
