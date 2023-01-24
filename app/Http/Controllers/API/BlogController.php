<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Blogs;
use Illuminate\Http\Request;
use App\Http\Resources\BlogsResource;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BlogController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blog = Blogs::all();
    
        return $this->sendResponse(BlogsResource::collection($blog), 'Blogs retrieved successfully.');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['users_id'] = Auth::id();  // current logged in user
   
        $validator = Validator::make($input, [
            'users_id' => 'required|integer|exists:users,id',
            'title' => 'required',
            'article' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $blog = Blogs::create($input);
   
        return $this->sendResponse(new BlogsResource($blog), 'Blogs created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($users_id)
    {
        $blog = DB::table('blogs')
        ->where(['users_id' => $users_id])
        ->get();
        // var_dump($blog); die;
  
        if (is_null($blog)) {
            return $this->sendError('Article not found.');
        }
   
        return $this->sendResponse($blog, 'Article retrieved successfully.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blogs $blog)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'string',
            'article' => 'string'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $blog->title = $input['title'];
        $blog->article = $input['article'];
        $blog->save();
   
        return $this->sendResponse(new BlogsResource($blog), 'Article updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blogs $blog)
    {
        $blog->delete();
   
        return $this->sendResponse([], 'Article deleted successfully.');
    }
}
