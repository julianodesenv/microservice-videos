<?php

namespace Tests\Unit\Models;

use App\Model\Genre;
use App\Model\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

# Classe especifica - vendor/bin/phpunit tests/Unit/CategoryTest.php
# Metodo especifico em um arquivo - vendor/bin/phpunit --filter testUseTraits tests/Unit/CategoryTest.php
# Metodo especifico de uma classe - vendor/bin/phpunit --filter CategortTest::testUseTraits

class GenreTest extends TestCase
{

    private $genre;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testFillableAttribute()
    {
        $fillable = ['name', 'is_active'];
        $this->assertEquals($fillable, $this->genre->getFillable());
    }

    public function testDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        foreach ($dates as $date) {
            $this->assertContains($date, $this->genre->getDates());
        }
        $this->assertCount(count($dates), $this->genre->getDates());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class, Uuid::class
        ];
        $genreTrairs = array_keys(class_uses(Genre::class));
        $this->assertEquals($traits, $genreTrairs);
    }

    public function testCastsAttribute()
    {
        $casts = ['id' => 'string', 'is_active' => 'boolean'];
        $this->assertEquals($casts, $this->genre->getCasts());
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->genre->incrementing);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = new Genre();
    }
}
