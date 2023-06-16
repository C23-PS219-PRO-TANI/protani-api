<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Image;
use App\Models\Paragraph;
use App\Models\Video;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // show all article
    public function index()
    {
        return response()->json(['message' => '<function()> index'], 200);
    }

    public function createArticle(Request $request)
    {
        $isSuccessInsert = true;
        $errMessage = [];
        if (!$this->addParagraph($request)) {
            $isSuccessInsert = false;
            $errMessage[] = 'add paragraph failed';
        }
        if (!$this->addVideoLink($request)) {
            $isSuccessInsert = false;
            $errMessage[] = 'add video link failed';
        }
        if (!$this->uploadPhoto($request)) {
            $isSuccessInsert = false;
            $errMessage[] = 'upload photo failed';
        }

        if ($isSuccessInsert) {
            return response()->json(['message' => '<function()> createArticle'], 200);
        }

        return response()->json(
            [
                'message' => 'error',
                'data' => $errMessage
            ],
            500
        );
    }

    public function saveArticle(Request $request)
    {
        return response()->json(['message' => '<function()> saveArticle'], 200);
    }

    public function saveArticleHeader(Request $request)
    {
        $this->updateArticleHeader(1, "", "", "");
    }

    public function softDeleteArticle(Request $request)
    {
        return response()->json(['message' => '<function()> softDeleteArticle'], 200);
    }

    public function deleteArticle(int $id)
    {
        return response()->json(['message' => '<function()> deleteArticle'], 200);
    }

    public function viewArticle(int $id)
    {
        return response()->json(['message' => '<function()> viewArticle'], 200);
    }

    private function uploadPhoto(Request $request): bool
    {
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('photos'), $filename);

            // Save the photo details to the database
            $photoData = new Image();
            $photoData->filename = $filename;
            $photoData->save();

            // Check if the photo data was saved successfully
            if ($photoData->id) {
                return true;
            }

            return false;
        }

        return false;
    }

    private function addVideoLink(Request $request): bool
    {
        $videoLink = $request->input('video');

        // Save the video link to the database
        $videoLinkData = new Video();
        $videoLinkData->link = $videoLink;
        $videoLinkData->save();

        // Check if the video link data was saved successfully
        if ($videoLinkData->id) {
            return true;
        }
        return false;
    }

    private function addParagraph(Request $request): bool
    {
        $paragraphContent = $request->input('content');

        // Save the paragraph content to the database
        $paragraphData = new Paragraph();
        $paragraphData->content = $paragraphContent;
        $paragraphData->save();

        // Check if the paragraph data was saved successfully
        if ($paragraphData->id) {
            return true;
        }

        return false;
    }


    public function createArticleHeader(Request $request)
    {
        $articleHeader           = new Article();
        $articleHeader->title    = $request->title;
        $articleHeader->cover    = $request->cover;
        $articleHeader->metaData = $request->metaData;
        $articleHeader->save();

        return response(['id' => $articleHeader->id], 200);
    }


    private function updateArticleHeader(int $id, string $title, string $cover, string $metaData)
    {
        $articleHeader = Article::find($id);

        if (!$articleHeader) {
            return response(['error' => 'Article not found'], 404);
        }

        $articleHeader->title     = $title;
        $articleHeader->cover     = $cover;
        $articleHeader->meta_data = $metaData;
        $articleHeader->save();

        return response(['data' => $articleHeader], 200);
    }
}
