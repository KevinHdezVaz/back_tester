<?php
namespace App\Http\Controllers;

use App\Models\App;
use Stripe\Charge;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function process(Request $request, App $app)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $charge = Charge::create([
            'amount' => 500,
            'currency' => 'usd',
            'source' => $request->stripeToken,
            'description' => 'Publicar app: ' . $app->name,
        ]);

        $app->update(['is_published' => true]);
        return response()->json(['message' => 'App publicada con pago']);
    }
}