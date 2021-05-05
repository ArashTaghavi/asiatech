<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryController extends Controller
{

    /**
     * @return JsonResource
     */
    public function index(): JsonResource
    {
        return JsonResource::collection(SubCategory::all());
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
                'category_id' => 'required|exists:categories,id'
            ]
        );

        $sub_category = new SubCategory();

        $sub_category->fill($request->all());
        if ($request->hasFile('image'))
            $sub_category->fillImage($request->file('image'));
        $sub_category->save();

        return new JsonResource($sub_category);
    }


    /**
     * @param int $id
     * @return JsonResource
     */
    public function show(int $id): JsonResource
    {
        return new JsonResource(SubCategory::findOrFail($id));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResource
     */
    public function update(Request $request, int $id): JsonResource
    {
        $sub_category = SubCategory::findOrFail($id);

        $sub_category->fill($request->all());

        if ($request->hasFile('image')) {
            if ($sub_category->isDirty('image')) {

                $sub_category->unlinkOriginalImage();
                $sub_category->fillImage($request->file('image'));
            } else {
                $sub_category->fill($request->except('image'));
            }
        }

        $sub_category->saveOrFail();

        return new JsonResource($sub_category);
    }

    /**
     * @param int $id
     * @return JsonResource
     */
    public function destroy(int $id): JsonResource
    {
        $sub_category = SubCategory::findOrFail($id);

        $sub_category->unlinkOriginalImage();

        $sub_category->delete();

        return new JsonResource($sub_category);
    }
}
