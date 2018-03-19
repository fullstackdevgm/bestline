<?php

use Api\OrderController;
use Illuminate\Http\JsonResponse;

class OrderSeederTest extends TestCase
{
    public function testSeededOrders()
    { 
      echo "Running OrderSeederTest...\n";

      echo " - Running Hansen/Remake test...\n";
      $this->hansenRemakeTest();
    }

    public function setUp() {
      parent::setUp();

      echo "Setting up OrderSeeder Test...\n";
      echo " - Setting up database...\n";
      echo " - Running migrations...\n";
      Artisan::call('migrate');
      echo " - Seeding Database...\n";
      Artisan::call('db:seed');
    }
    
    public function tearDown()
    {
      parent::tearDown();

      echo "Tearing down database...\n";
      //Artisan::call('migrate:refresh --force');
      DB::statement('DROP DATABASE bestline_testing;');
      DB::statement('CREATE DATABASE bestline_testing');
    }

    private function hansenRemakeTest() {

      $order = Order::where('sidemark', '=', 'Hansen/Remake')->firstOrFail();

      //headerboard should return as lining
      $test = $order->headerboard_cover_fabric->type->type;
      $expect = 'lining';
      $this->assertEquals($expect, $test);

      //fabric cut length for order item one should be 88
      $test = $order->orderLines[0]->getFabricCutLength($order->fabrics[0]);
      $expect = 88;
      $this->assertEquals($expect, $test);

      //total panels should be 15
      $test = $order->orderLines[0]->total_panels;
      $expect = 15;
      $this->assertEquals($expect, $test);

      //ring spacing should be 56.25
      $test = $order->orderLines[0]->ring_spacing;
      $expect = 56.25;
      $this->assertEquals($expect, $test);

      //assembler notes should appear in correct order
      $test = $order->orderLines[0]->assembler_notes;
      $expect = ['Has Return', 'Tie up: 53.000', 'Height to Rod', 'Rod Inside'];
      $this->assertEquals($expect, $test);
    }
}