<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Model\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Lang;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$category->toArray()]);
    }

    public function testShow()
    {
        $category = factory(Category::class)->create();
        //$response = $this->get(route('categories.show', $category->id));
        $response = $this->get(route('categories.show', ['category' => $category->id]));

        $response
            ->assertStatus(200)
            ->assertJson([$category->toArray()]);
    }

    public function testInvalidationData()
    {
        //$response = $this->post(route('categories.store', []));|
        $response = $this->json('POST', route('categories.store', []));
        $this->assertInvalidationRequired($response);

        $response = $this->json('POST', route('categories.store', [
            'name' => str_repeat('a', 256),
            'is_active' => 'a'
        ]));
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);

        $category = factory(Category::class)->create();
        $response = $this->json('PUT', route('categories.update', ['category' => $category->id]), []);
        $this->assertInvalidationRequired($response);

        $response = $this->json('PUT', route('categories.update', ['category' => $category->id]), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a'
        ]);
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);
    }

    protected function assertInvalidationRequired(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonMissingValidationErrors(['is_active'])
            ->assertJsonFragment([
                Lang::get('validation.required', ['attribute' => 'name'])
            ]);
    }

    protected function assertInvalidationMax(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])
            ]);
    }

    protected function assertInvalidationBoolean(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                Lang::get('validation.boolean', ['attribute' => 'is active'])
            ]);
    }

    public function testStore()
    {
        $response = $this->json('POST', route('categories.store'), [
            'name' => 'test'
        ]);

        $id = $response->json('id');
        $category = Category::find($id);

        $response
            ->assertStatus(201)
            ->assertJson($category->toArray());
        $this->assertTrue($response->json('is_active'));
        $this->assertNull($response->json('description'));

        #v2
        $response = $this->json('POST', route('categories.store'), [
            'name' => 'test',
            'description' => 'description',
            'is_active' => false
        ]);

        $response->assertJsonFragment([
           'is_active' => false,
            'description' => 'description'
        ]);
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create([
            'description' => 'description',
            'is_active' => false
        ]);
        $response = $this->json('PUT', route('categories.update', ['category' => $category->id]), [
            'name' => 'test',
            'description' => 'test',
            'is_active' => true
        ]);

        $id = $response->json('id');
        $category = Category::find($id);

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray())
            ->assertJsonFragment([
                'description' => 'test',
                'is_active' => true
            ]);

        #v2
        $response = $this->json('PUT', route('categories.update', ['category' => $category->id]), [
            'name' => 'test',
            'description' => ''
        ]);
        $response->assertJsonFragment([
            'description' => null
        ]);

        #v3
        $category->description = 'test';
        $category->save();

        $response = $this->json('PUT', route('categories.update', ['category' => $category->id]), [
            'name' => 'test',
            'description' => null
        ]);
        $response->assertJsonFragment([
            'description' => null
        ]);

    }

    public function testDestroy()
    {
        $category = factory(Category::class)->create();

        $response = $this->json('DELETE', route('categories.destroy', ['category' => $category->id]));
        $response->assertStatus(204);

        $this->assertNull(Category::find($category->id));
        $this->assertNotNull(Category::withTrashed()->find($category->id));
    }

}
