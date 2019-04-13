<?php

namespace adamhut\Wink\Http\Controllers;

use adamhut\Wink\Wink;
use Illuminate\Support\Str;
use adamhut\Wink\WinkCategory;
use Illuminate\Validation\Rule;
use adamhut\Wink\Http\Middleware\WinkAdmin;
use adamhut\Wink\Http\Resources\CategoriesResource;


class CategoriesController
{

    /**
     * Return posts.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $entries = WinkCategory::when(request()->has('search'), function ($q) {
                $q->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->orderBy('created_at', 'DESC')
            ->withCount('posts')
            ->get();

        return CategoriesResource::collection($entries);
    }

    /**
     * Return a single category.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id = null)
    {

        if ($id === 'new') {
            return response()->json([
                'entry' => WinkCategory::make([
                    'id' => Str::uuid(),
                ]),
            ]);
        }

        $entry = WinkCategory::findOrFail($id);

        return response()->json([
            'entry' => $entry,
        ]);
    }

    /**
     * Store a single category.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id)
    {
        Wink::abortIfNotAdmin();

        $data = [
            'name' => request('name'),
            'slug' => request('slug'),
            'meta' => request('meta', (object)[]),
        ];

        validator($data, [
            'name' => 'required',
            'slug' => 'required|' . Rule::unique(config('wink.database_connection') . '.wink_tags', 'slug')->ignore(request('id')),
        ])->validate();

        $entry = $id !== 'new' ? WinkCategory::findOrFail($id) : new WinkCategory(['id' => request('id')]);

        $entry->fill($data);

        $entry->save();

        return response()->json([
            'entry' => $entry->fresh(),
        ]);
    }

    /**
     * Return a single category.
     *
     * @param  string  $id
     * @return void
     */
    public function delete($id)
    {
        Wink::abortIfNotAdmin();

        $entry = WinkCategory::findOrFail($id);

        $entry->delete();
    }
}
