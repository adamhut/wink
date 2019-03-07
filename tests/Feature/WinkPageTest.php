<?php
namespace adamhut\Wink\Tests\Feature;


use adamhut\Wink\WinkPage;
use adamhut\Wink\WinkPost;
use adamhut\Wink\WinkCategory;
use adamhut\Wink\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WinkPageTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function a_category_can_be_created_with_the_factory()
    {
        $categorty = factory(WinkCategory::class)->create();

        $this->assertCount(1, WinkCategory::all());
    }

    /** @test */
    public function a_post_can_be_created_with_the_factory()
    {
        $post = factory(WinkPost::class)->create();

        $this->assertCount(1, WinkPost::all());
    }

    /** @test */
    public function a_post_can_associate_with_a_category()
    {
        $category = factory( WinkCategory::class)->create()->fresh();

        $post = factory(WinkPost::class)->create();

        $post->categories()->attach( $category);

        $this->assertCount(1,\DB::table( 'wink_post_category')->get());

        $this->assertEquals( $category->id, $post->categories->first()->id);

    }




}

