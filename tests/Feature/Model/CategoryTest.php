<?php

namespace Tests\Feature\Model;

use App\Model\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        factory(Category::class, 1)->create();
        /*
        $category = Category::create([
            'name' => 'test 1'
        ]);
        */

        $categories = Category::all();
        $this->assertCount(1, $categories);

        $categoryKey = array_keys($categories->first()->getAttributes());
        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $categoryKey
        );

    }

    public function testCreate()
    {
        $category = Category::create([
           'name' => 'test 1'
        ]);
        $category->refresh();
        $this->assertEquals('test 1', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);

        $category = Category::create([
            'name' => 'test 1',
            'description' => null
        ]);
        $this->assertNull($category->description);

        $category = Category::create([
            'name' => 'test 1',
            'description' => 'teste_description'
        ]);
        $this->assertEquals('teste_description', $category->description);

        $category = Category::create([
            'name' => 'test 1',
            'is_active' => false
        ]);
        $this->assertFalse($category->is_active);

        $category = Category::create([
            'name' => 'test 1',
            'is_active' => true
        ]);
        $this->assertTrue($category->is_active);

        $category = Category::create([
            'name' => 'test 1',
            'is_active' => true
        ]);

        $valid = Uuid::isValid($category->id);
        $this->assertTrue($valid);
    }

    public function testUpdate()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create([
            'description' => 'test_description',
            'is_active' => false
        ])->first();

        $data = [
            'name' => 'test_name_updated',
            'description' => 'test_description',
            'is_active' => true
        ];
        $category->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }
    }

    public function testDestroy()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create([
            'name' => 'teste 01',
            'description' => 'test_description',
            'is_active' => false
        ])->first();

        $delete = $category->delete();
        $this->assertTrue($delete);
    }

}
