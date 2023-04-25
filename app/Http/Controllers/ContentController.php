<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use Auth;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contents = Content::where('user_id', Auth::user()->id)->get();
        return view('contents.index', compact('contents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contents.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'type' => 'required',
            'file' => 'required|file|max:10240', // max 10MB
            'price' => 'required|numeric|min:0',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('public/content_files');

        $content = new Content();
        $content->user_id = Auth::user()->id;
        $content->title = $request->input('title');
        $content->description = $request->input('description');
        $content->type = $request->input('type');
        $content->file_path = $filePath;
        $content->price = $request->input('price');
        $content->save();

        return redirect()->route('contents.index')->with('success', 'Content created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $content = Content::findOrFail($id);
        return view('contents.show', compact('content'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $content = Content::findOrFail($id);
        return view('contents.edit', compact('content'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Content $content)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'type' => 'required',
            'price' => 'required',
            'file_path' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);
        
        $content->title = $request->input('title');
        $content->description = $request->input('description');
        $content->type = $request->input('type');
        $content->price = $request->input('price');

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');
            $content->file_path = '/storage/' . $path;
        }

        $content->save();

        return redirect()->route('content.index')->with('success', 'Content updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $content = Content::findOrFail($id);
        Storage::disk('public')->delete(str_replace('/storage', '', $content->file_path));
        $content->delete();
        return redirect()->route('content.index')->with('success', 'Content deleted successfully.');
    }
}
