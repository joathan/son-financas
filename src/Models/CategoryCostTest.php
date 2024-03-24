<?php

use PHPUnit\Framework\TestCase;
use SONFin\Models\CategoryCost;

class CategoryCostTest extends TestCase
{
    public function testFillable()
    {
        $fillable = [
            'name',
            'user_id',
        ];

        $categoryCost = new CategoryCost();

        $this->assertEquals($fillable, $categoryCost->getFillable());
    }
}