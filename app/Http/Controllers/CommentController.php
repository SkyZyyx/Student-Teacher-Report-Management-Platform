<?php

namespace App\Http\Controllers;

use App\Models\Compterendu;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Compterendu $compterendu){
        //$compterendu->user_id = auth()->id();
        $comment = new Comment();
        $comment->compterendu_id = $compterendu->id;
        $comment->content = request('content');
        $comment->save();

        return redirect()->route('Compterendu.index',$compterendu->id);

    }
}
