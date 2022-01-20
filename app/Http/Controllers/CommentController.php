<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    //get data
    function getData($id)
    {
        //return Comment::all();


        // $request = DB::table('users')
        //     ->join('comments', 'users.id', '=', 'comments.userId')
        //     ->select('comments.id', 'comments.body',  'users.userName', 'comments.userId', 'comments.parentId', 'comments.createdAt')
        //     ->get();
        // return $request;

        $request = DB::table('users')
            ->join('comments', 'users.id', '=', 'comments.userId')
            ->join('categories', 'comments.category_id', '=', 'categories.id')
            ->select('comments.id', 'comments.body',  'users.userName', 'comments.userId', 'comments.parentId', 'comments.createdAt')
            ->where('categories.id', '=', $id)
            ->orderBy('createdAt', 'desc')
            ->get();
        return $request;
    }


    //add $id is the id of the category
    function add(Request $req, $id)
    {
        $comment = new Comment;
        $comment->body = $req->body;
        $comment->userId = $req->userId;
        $comment->parentId = $req->parentId;
        $comment->createdAt = $req->createdAt;
        $comment->category_id = $id;

        //result
        $result = $comment->save();
        if ($result) {
            return ["Result" => "Data has been saved", "commentId" => $comment->id];
        } else {
            return ["Result" => "operation failed"];
        }
    }

    //update
    function update(Request $req, $id)
    {
        $comment = Comment::find($id);
        $comment->body = $req->input('body');

        //result
        $result = $comment->update();
        if ($result) {
            return ["Result" => "Data has been updated"];
        } else {
            return ["Result" => "operation failed"];
        }
    }

    // delete
    function delete($id)
    {

        $result = Comment::where('id', $id)->orWhere('parentId', $id)->delete();
        if ($result) {
            return ["result" => "record has been deleted"];
        } else {
            return ["result" => "Operation delete has been deleted"];
        }

        //     $comment = Comment::find($id);
        //     $result = $comment->delete();

        //     if ($result) {
        //         return ["result" => "record has been deleted"];
        //     } else {
        //         return ["result" => "Operation delete has been deleted"];
        //     }
    }
}
