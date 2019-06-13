<?php

namespace adamhut\Wink\Http\Controllers;

use adamhut\Wink\WinkTag;
use adamhut\Wink\WinkPost;
use Illuminate\Support\Str;
use adamhut\Wink\WinkCategory;
use Illuminate\Validation\Rule;
use adamhut\Wink\Http\Resources\PostsResource;

class PostsController
{
    /**
     * Return posts.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $entries = WinkPost::when(request()->has('search'), function ($q) {
            $q->where('title', 'LIKE', '%'.request('search').'%');
        })->when(request('status'), function ($q, $value) {
            $q->$value();
        })->when(request('author_id'), function ($q, $value) {
            $q->whereAuthorId($value);
        })->when(request('tag_id'), function ($q, $value) {
            $q->whereHas('tags', function ($query) use ($value) {
                $query->where('id', $value);
            });
        })->when(request('category_id'), function ($q, $value) {
            $q->whereHas('categories', function ($query) use ($value) {
                $query->where('id', $value);
            });
        })
            ->orderBy('created_at', 'DESC')
            ->with('tags', 'categories')
            ->paginate(30);

        return PostsResource::collection($entries);
    }

    /**
     * Return a single post.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id = null)
    {
        if ($id === 'new') {
            return response()->json([
                'entry' => WinkPost::make(['id' => Str::uuid(), 'publish_date' => now()->format('Y-m-d H:i:00')]),
            ]);
        }

        $entry = WinkPost::with('tags', 'categories')->findOrFail($id);

        return response()->json([
            'entry' => $entry,
        ]);
    }

    /**
     * Store a single post.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id)
    {
        $data = [
            'title' => request('title'),
            'excerpt' => request('excerpt', ''),
            'slug' => request('slug'),
            'body' => request('body', ''),
            'published' => request('published'),
            'author_id' => request('author_id'),
            'featured_image' => request('featured_image'),
            'featured_image_caption' => request('featured_image_caption', ''),
            'publish_date' => request('publish_date', ''),
            'meta' => request('meta', (object) []),
        ];

        validator($data, [
            'publish_date' => 'required|date',
            'author_id' => 'required',
            'title' => 'required',
            'slug' => 'required|'.Rule::unique(config('wink.database_connection').'.wink_posts', 'slug')->ignore(request('id')),
        ])->validate();

        $entry = $id !== 'new' ? WinkPost::findOrFail($id) : new WinkPost(['id' => request('id')]);

        $entry->fill($data);

        $entry->save();

        $entry->tags()->sync(
            $this->collectTags(request('tags'))
        );

        $entry->categories()->sync(
            $this->collectCategories(request('categories'))
        );

        return response()->json([
            'entry' => $entry,
        ]);
    }

    /**
     * Tags incoming from the request.
     *
     * @param  array  $incomingTags
     * @return array
     */
    private function collectTags($incomingTags)
    {
        $allTags = WinkTag::all();

        return collect($incomingTags)->map(function ($incomingTag) use ($allTags) {
            $tag = $allTags->where('slug', Str::slug($incomingTag['name']))->first();

            if (!$tag) {
                return false;
            }
            // if (! $tag) {
            //     $tag = WinkTag::create([
            //         'id' => $id = Str::uuid(),
            //         'name' => $incomingTag['name'],
            //         'slug' => Str::slug($incomingTag['name']),
            //     ]);
            // }

            return (string) $tag->id;
        })->filter(function($tag){
            return $tag;
        })
        ->toArray();
    }

    /**
     * Categories incoming from the request.
     *
     * @param  array  $incomingCategories
     * @return array
     */
    private function collectCategories($incomingCategories)
    {
        $allCategories = WinkCategory::all();

        return collect($incomingCategories)->map(function ($incomingCategory) use ($allCategories) {
            $category = $allCategories->filter(function($category) use( $incomingCategory) {
                                return $category->slug == Str::slug($incomingCategory['name']) ||
                                    $category->name == $incomingCategory['name'];
                            })
                            ->first();
            if(!$category)
            {
                return false;
            }
            // if (! $category) {
            //     $category = WinkCategory::create([
            //         'id' => $id = Str::uuid(),
            //         'name' => $incomingCategory['name'],
            //         'slug' => Str::slug($incomingCategory['name']),
            //     ]);
            // }

            return (string) $category->id;
        })->filter(function ($category) {
            return $category;
        })->toArray();
    }

    /**
     * Return a single post.
     *
     * @param  string  $id
     * @return void
     */
    public function delete($id)
    {
        $entry = WinkPost::findOrFail($id);

        $entry->delete();
    }
}
