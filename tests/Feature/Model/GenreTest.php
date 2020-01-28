<?php

namespace Tests\Feature\Model;

use App\Model\Category;
use App\Model\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GenreTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        factory(Genre::class, 1)->create();
        /*
        $genre = Genre::create([
            'name' => 'test 1'
        ]);
        */

        $genries = Genre::all();
        $this->assertCount(1, $genries);

        $genreKey = array_keys($genries->first()->getAttributes());
        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $genreKey
        );

    }

    public function testCreate()
    {
        //name
        $genre = Genre::create([
            'name' => 'test 1'
        ]);
        $genre->refresh();
        $this->assertEquals('test 1', $genre->name);
        $this->assertTrue($genre->is_active);

        //active false
        $genre = Genre::create([
            'name' => 'test_1',
            'is_active' => false
        ]);
        $this->assertFalse($genre->is_active);

        //uuid
        $genre = Genre::create([
            'name' => 'name',
            'is_active' => true
        ]);
        $valid = Uuid::isValid($genre->id);
        $this->assertTrue($valid);
    }

    public function testUpdate()
    {
        /** @var Genre $genre */
        $genre = factory(Genre::class)->create([
            'name' => 'test_name',
            'is_active' => false
        ])->first();

        $data = [
            'name' => 'test_name_updated',
            'is_active' => true
        ];
        $genre->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $genre->{$key});
        }
    }

    public function testDestroy()
    {
        /** @var Genre $genre */
        $genre = factory(Genre::class)->create([
            'name' => 'teste 01',
            'is_active' => false
        ])->first();

        $delete = $genre->delete();
        $this->assertTrue($delete);
    }

}
