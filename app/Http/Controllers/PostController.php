<?php

namespace App\Http\Controllers;

use App\Post;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\PostResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    public function __construct(){
        return $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post = Post::paginate(5);
        return new PostCollection($post);
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $post = $request->all();
        // Post::create($post);

        $validator = Validator::make($post, [
            'title' => ['required', 'min:5']
        ]);

        if ($validator->fails()) {
            # code...
            return response()->json($validator->errors(), 400);
        }

        $response = $request->user()->posts()->create($post);

        return response()->json($response, 201);
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
        $post = Post::find($id);

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $update = $request->all();

        if ($request->user()->id == $post['user_id']) {
            # code...
            $validator = Validator::make($update, [
                'title' => ['required', 'min:5']
            ]);
    
            if ($validator->fails()) {
                # code...
                return response()->json($validator->errors(), 400);
            }

            $post->update($update);
            return response()->json("sukses", 200);
        }

        return response()->json("unauthorized", 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $post = Post::find($id);
        if (is_null($post)) {
            # code...
            return response()->json(['message' => 'Data not found'], 404);
        }else{
            if ($request->user()->id == $post->user_id) {
                # code...
                $post->delete();
    
                return response()->json('deleted', 200);
            }
    
            return response()->json(['message' => 'unauthorized'], 400);
        }
    }
}
