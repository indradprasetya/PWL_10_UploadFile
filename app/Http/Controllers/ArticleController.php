<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF as PDF;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->file('image')) {
            $image_name = $request->file('image')->store('images', 'public');
        }

        Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'featured_image' => $image_name
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $article = Article::find($article->id);
        return view('articles.edit', ['article' => $article]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $article = Article::find($id);

        $article->update([
            'title' => $request->title,
            'content' => $request->content
        ]);

        if ($request->file('image')) {
            if ($article->featured_image && file_exists(storage_path('app/public/' . $article->featured_image))) {
                Storage::delete('public/' . $article->featured_image);
            }
            $image_name = $request->file('image')->store('images', 'public');
            $article->featured_image = $image_name;
        }
        $article->save();
        return 'Article successfully updated';

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }

    /**
     * Generate PDF
     */
    public function cetak_pdf()
    {
        $articles = Article::all();
        $pdf = 'PDF'::loadview('articles.articles_pdf', ['articles' => $articles]);
        return $pdf->stream();
    }
}
