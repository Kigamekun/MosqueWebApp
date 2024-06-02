<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if (isset($_GET['type'])) {
            $type = $_GET['type'];
            $blogs = Blog::where('type', $type)->paginate(100);
        } elseif (isset($_GET['search'])) {
            $search = $_GET['search'];
            $blogs = Blog::where('title', 'like', '%' . $search . '%')->paginate(100);
        } else {
            $blogs = Blog::paginate(100);
        }
        return response()->json(['message' => 'Data berhasil di load', 'status' => 'success','data' => $blogs, 'statusCode' => 200], 200);
    }


    public function store(Request $request)
    {
        dd($request->all());
        try {
            $request->validate([
                'title' => 'required',
                'content' => 'required',
                'type' => 'required',
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Data tidak lengkap', 'status' => 'error', 'statusCode' => 400,'errors' => $th->validator->errors()], 400);
        }

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->type = $request->type;
        $blog->user_id = auth()->user()->id;
        $blog->slug = \Str::slug($request->title);

        $blog->date = now();
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '-' . $file->getClientOriginalName();
            Storage::disk('public')->put('blog/'.$filename, file_get_contents($file));
            $blog->thumbnail = $filename;
        }
        $blog->save();

        return response()->json(['message' => 'Blog berhasil di tambahkan', 'status' => 'success','data' => $blog, 'statusCode' => 200], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required',
                'content' => 'required',
                'type' => 'required',
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Data tidak lengkap', 'status' => 'error', 'statusCode' => 400,'errors' => $th->validator->errors()], 400);
        }

        $blog = Blog::findOrFail($id);
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->type = $request->type;
        $blog->user_id = auth()->user()->id;
        $blog->slug = \Str::slug($request->title);

        $blog->date = now();
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '-' . $file->getClientOriginalName();
            Storage::disk('public')->put('blog/'.$filename, file_get_contents($file));
            $blog->thumbnail = $filename;
        }
        $blog->save();

        return response()->json(['message' => 'Blog berhasil di update', 'status' => 'success','data' => $blog, 'statusCode' => 200], 200);
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->thumbnail) {
            $filePath = Storage::disk('public')->path('blog/'.$blog->thumbnail);
            File::delete($filePath);
        }
        $blog->delete();

        return response()->json(['message' => 'Blog berhasil di hapus', 'status' => 'success', 'statusCode' => 200], 200);
    }
}
