<?php
namespace App\Http\Controllers;

use App\Models\App;
use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request, App $app)
    {
        $testerId = $request->input('tester_id', 'anonymous');

        if (!Test::where('tester_id', $testerId)->where('app_id', $app->id)->exists()) {
            Test::create(['tester_id' => $testerId, 'app_id' => $app->id]);
            return response()->json(['message' => 'Prueba registrada']);
        }
        return response()->json(['message' => 'Ya probaste esta app'], 400);
    }
}