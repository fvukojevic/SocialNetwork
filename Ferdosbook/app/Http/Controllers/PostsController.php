<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Posts;
use Auth;

class PostsController extends Controller
{
    /**
     * Goes to home page with all posts that belong to logged users friends
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = [];
        $user['user_id'] = Auth::user()->id;
        $client = $this->getClient();
        $res = $client->post('http://localhost:8888/post/getPosts', ['body' => json_encode($user)]);

        $posts = json_decode($res->getBody()->getContents());
        $postsCollection = collect($posts);
        return view('home')->with('posts', $postsCollection);
    }

    /**
     * Finds the single post by id
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function singlePost($id)
    {
        $arr = [];
        $arr['id'] = (int)$id;
        $client = $this->getClient();
        $res = $client->post('http://localhost:8888/post/getPost', ['body' => json_encode($arr)]);

        $post = json_decode($res->getBody()->getContents());
        $postCollection = collect($post);
        return view('posts.singlePost')->with('post',$postCollection[0]);
    }

    /**
     * Stores as post inside the database
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

      $post = new Posts;
      $post->user_id = Auth::user()->id;
      $post->content = $request->content;
      $post->status = 1;

      $client = $this->getClient();
      $res = $client->post('http://localhost:8888/post', ['body' => json_encode($post)]);

      if($res->getStatusCode() === 200){
          return back()->with('success', 'Post created successfully');
      }
      return back()->with('error', 'Oooops something went wrong');
    }


    /**
     * Edit page for post
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $arr = [];
        $arr['id'] = (int)$id;
        $client = $this->getClient();
        $res = $client->post('http://localhost:8888/post/getPost', ['body' => json_encode($arr)]);

        if($res->getStatusCode() === 200){
            $post = json_decode($res->getBody()->getContents());
            $postCollection = collect($post);
            return view('posts.editPost')->with('post',$postCollection[0]);
        }
    }

    /**
     * Updating the post to database
     *
     * @param $id
     */
    public function update(Request $request, $id)
    {
        $post = new Posts;
        $post->id = (int)$id;
        $post->user_id = Auth::user()->id;
        $post->content = $request->content;
        $post->status = 1;

        $client = $this->getClient();
        $client->post('http://localhost:8888/post/updatePost', ['body' => json_encode($post)]);

        return redirect('/posts/singlePost/' . $id)->with('success', 'Post updated successfully');
    }

    /**
     * Deleting the post from the database
     *
     * @param $id
     */
    public function delete($id)
    {
        $client = $this->getClient();
        $client->post('http://localhost:8888/post/deletePost/' . $id);

        return back()->with('success', 'Post deleted successfully');
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
