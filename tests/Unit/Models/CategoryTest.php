<?php

namespace Tests\Unit\Models;

use App\Model\Category;
use App\Model\Genre;
use App\Model\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

# Classe especifica - vendor/bin/phpunit tests/Unit/CategoryTest.php
# Metodo especifico em um arquivo - vendor/bin/phpunit --filter testUseTraits tests/Unit/CategoryTest.php
# Metodo especifico de uma classe - vendor/bin/phpunit --filter CategortTest::testUseTraits

class CategoryTest extends TestCase
{

    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = new Category();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testFillableAttribute()
    {
        $fillable = ['name', 'description', 'is_active'];
        $this->assertEquals($fillable, $this->category->getFillable());
    }

    public function testDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        foreach ($dates as $date) {
            $this->assertContains($date, $this->category->getDates());
        }
        $this->assertCount(count($dates), $this->category->getDates());
        //$this->assertEquals($dates, $this->category->getDates());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class, Uuid::class
        ];
        $categoryTrairs = array_keys(class_uses(Category::class));
        //dd($traits, $categoryTrairs);
        $this->assertEquals($traits, $categoryTrairs);
    }

    public function testCastsAttribute()
    {
        $casts = ['id' => 'string', 'is_active' => 'boolean'];
        $this->assertEquals($casts, $this->category->getCasts());
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->category->incrementing);
    }
}
