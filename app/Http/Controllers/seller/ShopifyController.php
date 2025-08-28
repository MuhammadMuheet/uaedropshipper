<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShopifyController extends Controller
{


    public function redirectToShopify(Request $request)
    {
        $storeUrl = $request->query('store');
        $scopes = 'read_orders,read_products';
        $redirectUri = urlencode(env('SHOPIFY_REDIRECT_URI'));

        $installUrl = "https://{$storeUrl}/admin/oauth/authorize?client_id=" . env('SHOPIFY_API_KEY') . "&scope={$scopes}&redirect_uri={$redirectUri}&state=nonce123";

        return redirect()->away($installUrl);
    }

    public function handleCallback(Request $request)
    {
        $params = $request->query();

        if (! $this->verifyHmac($params)) {
            abort(403, 'Invalid HMAC signature.');
        }

        $code = $params['code'];
        $store = $params['shop'];

        $response = Http::asForm()->post("https://{$store}/admin/oauth/access_token", [
            'client_id' => env('SHOPIFY_API_KEY'),
            'client_secret' => env('SHOPIFY_API_SECRET'),
            'code' => $code,
        ]);

        if ($response->successful()) {
            $accessToken = $response->json()['access_token'];

            // ✅ Save to DB
            $user = auth()->user();
            $user->shopify_domain  = $store;
            $user->shopify_token = $accessToken;
            $user->save();

            return redirect()->route('seller_profile')->with('success', 'Connected to Shopify!');
        }

        Log::error('Shopify OAuth failed', ['response' => $response->body()]);

        return redirect()->route('seller_profile')->with('error', 'Shopify authorization failed.');
    }


    private function verifyHmac(array $params): bool
    {
        $hmac = $params['hmac'] ?? null;
        unset($params['hmac'], $params['signature']);

        ksort($params);
        $query = urldecode(http_build_query($params));
        $calculated = hash_hmac('sha256', $query, env('SHOPIFY_API_SECRET'));

        return hash_equals($hmac, $calculated);
    }
}