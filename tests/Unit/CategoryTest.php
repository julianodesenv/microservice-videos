<?php

namespace Tests\Unit;

use App\Model\Category;
use App\Model\Genre;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;
use App\Model\Traits\Uuid;

# Classe especifica - vendor/bin/phpunit tests/Unit/CategoryTest.php
# Metodo especifico em um arquivo - vendor/bin/phpunit --filter testUseTraits tests/Unit/CategoryTest.php
# Metodo especifico de uma classe - vendor/bin/phpunit --filter CategortTest::testUseTraits

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testFillableAttribute()
    {
        $fillable = ['name', 'description', 'is_active'];
        $category = new Category();
        $this->assertEquals($fillable, $category->getFillable());
    }

    public function testDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        $category = new Category();
        foreach ($dates as $date){
            $this->assertContains($date, $category->getDates());
        }
        $this->assertCount(count($dates), $category->getDates());
        //$this->assertEquals($dates, $category->getDates());
    }

    public function testIfUseTraits()
    {
        Genre::create(['name' => 'test']);
        $traits = [
            SoftDeletes::class, Uuid::class
        ];
        $categoryTrairs = array_keys(class_uses(Category::class));
        //dd($traits, $categoryTrairs);
        $this->assertEquals($traits, $categoryTrairs);
    }

    public function testCasts()
    {
        $casts = ['id' => 'string'];
        $category = new Category();
        $this->assertEquals($casts, $category->getCasts());
    }

    public function testIncrementing()
    {
        $category = new Category();
        $this->assertFalse($category->incrementing);
    }
}
