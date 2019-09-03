<?php

namespace App\Http\Controllers;

use App\Comments;
use App\notifications;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Auth;

class CommentsController extends Controller
{
    /**
     * Stores as comment inside the database
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'content'=>'required'
        ]);

        $comment = new Comments;
        $comment->user_id = Auth::user()->id;
        $comment->post_id = $request->post_id;
        $comment->content = $request['content'];

        $client = $this->getClient();
        $res = $client->post('http://localhost:8888/comment', ['body' => json_encode($comment)]);

        if($res->getStatusCode() === 200){
            $postRes = $client->post('http://localhost:8888/post/getPost', ['body' => json_encode(['id' => (int)$request->post_id])]);
            $post = json_decode($postRes->getBody()->getContents());

            $notification = new notifications;
            $notification->user_sender_id = $post[0]->user_id;
            $notification->user_accepter_id = Auth::user()->id;
            $notification->note = "commented on your post:<i><a style=\"text-decoration:inherit; color:inherit;\" href= ".url("/posts/singlePost/".$post[0]->ID).">" . $post[0]->content . "</a></i>";
            $notification->status = '1';
            $notification->post_id = $post[0]->ID;
            $notification->save();

            return back()->with('success', 'Comment created successfully');
        }
        return back()->with('error', 'Oooops something went wrong');
    }

    /**
     * Deleting the comment from the database
     *
     * @param $id
     */
    public function delete($id)
    {
        $client = $this->getClient();
        $client->post('http://localhost:8888/comment/deleteComment/' . $id);

        return back()->with('success', 'Comment deleted successfully');
    }

    /**
     * Returns a GuzzleClient we use for go routing
     *
     * @return Client
     */
    private function getClient(){
        return new Client([
            'headers' => ['Content-Type' => 'application/json']
        ]);
    }
}