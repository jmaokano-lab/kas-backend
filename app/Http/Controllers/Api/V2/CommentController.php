<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogComment;
use App\Models\Blog;

class CommentController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'comment' => 'required',
            'blog_id' => 'required|exists:blogs,id',
        ]);

        $user = auth()->user();
        if (!$user) {
        return response()->json(['error' => 'Unauthorized - No user found'], 401);
    }

        $comment = BlogComment::create([
            'blog_id' => $request->blog_id,
            'user_id' => $user->id,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'data' => $comment->load('user','blog')
        ]);
    }


    public function comments($blog_id)
    {
        $blog = Blog::find($blog_id);

        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found']);
        }

        $comments = BlogComment::where('blog_id', $blog->id)
            ->with('user')
            ->orderBy('id','DESC')
            ->get();

        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }

    // Edit Comment
    public function update(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required'
        ]);

        $user = auth()->user();
        $comment = BlogComment::find($id);

        if(!$comment) {
            return response()->json(['success' => false, 'message' => 'Comment not found']);
        }

        if($comment->user_id != $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $comment->update([
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully',
            'data' => $comment->load('user')
        ]);
    }

    // Delete Comment
    public function destroy($id)
    {
        $user = auth()->user();
        $comment = BlogComment::find($id);

        if(!$comment) {
            return response()->json(['success' => false, 'message' => 'Comment not found']);
        }

        if($comment->user_id != $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }
}
