<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts=Post::all();
        return view('admin.posts',['pagename'=>'Posts','posts'=>$posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.posts.create',['pagename'=>'New Post']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'title' => 'required',
            'posts' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withInput($request->all())->withErrors($validator);
        }

        // if (!$request['new_id']) {
        //     Post::create([
        //         'title' => $request['title'],
        //         'subtitle' => $request['subtitle'],
        //         'post' => $request['posts']
        //     ]);
        // }
        $this->savePost($request->all());
        return redirect("admin/posts");
    }

    public static function savePost($request) {
        
        $data = array(
            'title' => $request['title'],
            'subtitle' => $request['subtitle'],
            'post' => $request['posts']
        );

        if (isset($request['image'])) {
            $data['image'] = $request['image'];
        }

        $id = Post::create($data);

        return $id;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post=Post::find($id);
        return view('admin.posts.edit',['pagename'=>'Edit Post', 'post'=>$post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(),[
            'title' => 'required',
            'posts' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withInput($request->all())->withErrors($validator);
        }

        $this->updatePost($request->all(),$id);
        return redirect("admin/posts");
    }

    public static function updatePost($request,$id=0) {
        
        $data = array(
            'title' => $request['title'],
            'subtitle' => $request['subtitle'],
            'post' => $request['posts']
        );

        if (isset($request['image'])) {
            $data['image'] = $request['image'];
        }
        
        // if (!$request['new_id']) {
            Post::find($id)->update($data);
        // }

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if ($post->image) {
            unlink(base_path().'/public/images/posts/'.$post->image);
            unlink(base_path().'/public/images/posts/thumbs/'.$post->image);
        }
        $post->delete();

        return redirect('admin/posts');
    }
}
