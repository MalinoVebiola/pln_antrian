<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\AdminController;

class AdminControllerTest extends TestCase
{
    public function test_index_method_returns_correct_view()
    {
        $controller = new AdminController();
        $response = $controller->index();

        // Memastikan bahwa fungsi index mengembalikan view yang benar
        $this->assertEquals('admin.index', $response->getName());
    }

    public function test_antrian_method_returns_correct_view()
    {
        $controller = new AdminController();
        $response = $controller->antrian();

        // Memastikan bahwa fungsi antrian mengembalikan view yang benar
        $this->assertEquals('admin.antrian', $response->getName());
    }
}
