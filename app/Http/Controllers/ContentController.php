<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContentRequest;
use App\Services\Content\CreateContentService;
use App\Services\Content\ContentListService;
use App\Services\Content\DeleteContentService;
use App\Services\Content\UpdateContentService;
use Illuminate\Http\Request;

class ContentController extends BaseController
{
    private static $Service;
    private static $CreateContent;
    private static $UpdateContent;
    private static $DeleteContent;

    public function __construct(
        ContentListService $Service,
        CreateContentService $createContent,
        UpdateContentService $updateContent,
        DeleteContentService $deleteContent
    ) {
        self::$Service = $Service;
        self::$CreateContent = $createContent;
        self::$UpdateContent = $updateContent;
        self::$DeleteContent = $deleteContent;
    }

    // frontend listing
    function index(Request $request)
    {
        $response = self::$Service->list($request);
        return response()->json($response, $response->code);
    }

    // admin listing
    function adminList(ContentRequest $request)
    {
        $response = self::$Service->adminList($request);
        return response()->json($response, $response->code);
    }

    // admin create
    function create(ContentRequest $request)
    {
        $response = self::$CreateContent->handle($request);
        return response()->json($response, $response->code);
    }

    // admin update
    function update(ContentRequest $request)
    {
        $response = self::$UpdateContent->handle($request);
        return response()->json($response, $response->code);
    }

    // admin delete
    function delete(ContentRequest $request)
    {
        $response = self::$DeleteContent->handle($request);
        return response()->json($response, $response->code);
    }
}
