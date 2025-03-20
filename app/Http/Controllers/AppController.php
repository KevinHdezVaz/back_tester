<?php
namespace App\Http\Controllers;

use App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppController extends Controller
{
    public function index()
    {
        return response()->json(App::where('is_published', true)->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'logo' => 'required|image|max:2048',
            'apk' => 'required|file|mimes:apk|max:10240',
        ]);

        $logoPath = $request->file('logo')->store('logos', 'public');
        $apkPath = $request->file('apk')->store('apks', 'public');

        $app = App::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'creator' => auth()->user()->name,
            'description' => $request->description,
            'category' => $request->category,
            'logo_path' => $logoPath,
            'apk_path' => $apkPath,
            'is_published' => false,
        ]);

        return response()->json(['app_id' => $app->id, 'needs_payment' => auth()->user()->credits < 1]);
    }

    public function publish(App $app)
    {
        if (auth()->user()->credits >= 1) {
            auth()->user()->decrement('credits');
            $app->update(['is_published' => true]);
            return response()->json(['message' => 'App publicada con créditos']);
        }
        return response()->json(['message' => 'Necesitas créditos o pagar'], 403);
    }
}