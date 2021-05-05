<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodController extends Controller
{

    /**
     * @return JsonResource
     */
    public function index(): JsonResource
    {
        return JsonResource::collection(Food::all());
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
                'price' => 'required|integer|max:100000000',
                'description' => 'required',
                'sub_category_id' => 'required|exists:sub_categories,id',
            ]
        );

        $food = new Food();

        $food->fill($request->all());
        if ($request->hasFile('image'))
            $food->fillImage($request->file('image'));
        $food->save();

        return new JsonResource($food);
    }


    /**
     * @param int $id
     * @return JsonResource
     */
    public function show(int $id): JsonResource
    {
        return new JsonResource(Food::findOrFail($id));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, int $id): JsonResource
    {

        $this->validate($request, [
                'title' => 'required',
                'price' => 'required|integer|max:100000000',
                'description' => 'required',
                'sub_category_id' => 'required|exists:sub_categories,id',
            ]
        );

        $food = Food::findOrFail($id);

        $food->fill($request->all());

        if ($request->hasFile('image')) {
            if ($food->isDirty('image')) {

                $food->unlinkOriginalImage();
                $food->fillImage($request->file('image'));
            } else {
                $food->fill($request->except('image'));
            }
        }

        $food->saveOrFail();

        return new JsonResource($food);
    }

    /**
     * @param int $id
     * @return JsonResource
     */
    public function destroy(int $id): JsonResource
    {
        $food = Food::findOrFail($id);

        $food->unlinkOriginalImage();

        return new JsonResource($food);
    }
}
