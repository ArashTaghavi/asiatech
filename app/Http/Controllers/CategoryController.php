<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryController extends Controller
{

    /**
     * @return JsonResource
     */
    public function index(): JsonResource
    {
        return JsonResource::collection(Category::all());
    }

    /**
     * @param Request $request
     * @return JsonResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResource
    {

        $this->validate($request, [
                'title' => 'required',
            ]
        );

        $category = new Category();

        $category->fill($request->all());
        if ($request->hasFile('image'))
            $category->fillImage($request->file('image'));
        $category->save();

        return new JsonResource($category);
    }


    /**
     * @param int $id
     * @return JsonResource
     */
    public function show(int $id): JsonResource
    {
        return new JsonResource(Category::findOrFail($id));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResource
     */
    public function update(Request $request, int $id): JsonResource
    {
        $category = Category::findOrFail($id);

        $category->fill($request->all());

        if ($request->hasFile('image')) {
            if ($category->isDirty('image')) {

                $category->unlinkOriginalImage();
                $category->fillImage($request->file('image'));
            } else {
                $category->fill($request->except('image'));
            }
        }

        $category->saveOrFail();

        return new JsonResource($category);
    }

    /**
     * @param int $id
     * @return JsonResource
     */
    public function destroy(int $id): JsonResource
    {
        $category = Category::findOrFail($id);

        $category->unlinkOriginalImage();

        $category->delete();

        return new JsonResource($category);
    }
}
