<?php
namespace adamhut\Wink\Tests\Feature;

use adamhut\Wink\WinkPost;
use adamhut\Wink\WinkCategory;
use adamhut\Wink\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WinkCategoryTest extends TestCase
{
    use RefreshDatabase;


    private function validParams($overrides = [])
    {

        return array_merge([
            'slug' => 'foo-bar',
            'name' => 'Foo Bar',
            'meta' => 'hello',
        ], $overrides);
    }


    /** @test */
    public function it_will_create_a_category()
    {
        $param = $this->validParams();
        $this->assertCount(0, WinkCategory::all());
        $this->get(route('wink.categories.index'));

    }


}
