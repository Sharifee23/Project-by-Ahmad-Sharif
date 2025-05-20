<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\product;
use App\Models\states;
use App\Models\Market;
use App\Models\Price;
use App\Models\User;
use App\Models\Activities;
use Carbon\Carbon;

class ChartController extends Controller
{

    public function index()
    {
        $products = Product::all();
        $firstProduct = $products->first();

        $markets = collect(); // Default empty collection

        if ($firstProduct) {
            $markets = Market::whereIn('id', function ($query) use ($firstProduct) {
                $query->select('market_id')
                    ->from('prices')
                    ->where('product_id', $firstProduct->id);
            })->get();
        }

        $firstMarket = $markets->first();

        // Get today's date
        $today = \Carbon\Carbon::today();

        $competitivePrices = [];
        foreach ($products as $product) {
            $competitivePrices[$product->id] = Price::where('product_id', $product->id)
                ->whereDate('recorded_date', $today)
                ->select('price', 'market_id')  // Select both price and market_id
                ->orderBy('price', 'asc')  // Ensure prices are sorted ascending to get the lowest
                ->first();  // Fetch the first (lowest) price
        }

        $product = Product::all()->count();
        $market = Market::all()->count();
        $state = states::all()->count();
        $user = User::all()->count();

        $activities = Activities::with(['user'])->orderBy('timestamp', 'desc')->limit(5)->get();
        $records = Price::with(['product', 'market'])->latest()->limit(5)->get();

        $c_price = Price::with(['product', 'market'])
        ->whereDate('recorded_date', today())
        ->whereRaw('price = (SELECT MIN(price) FROM prices AS p2 WHERE p2.product_id = prices.product_id AND DATE(p2.recorded_date) = CURDATE())')
        ->limit(20) // Limits the results to 5
        ->get();

        




        // Return the view with the competitive prices for all products
        return view('ezypay.index', compact('products', 'firstProduct', 'markets', 'firstMarket', 'competitivePrices', 'product', 'market', 'state', 'user', 'activities', 'records', 'c_price'));
    }




    public function getMarkets(Request $request)
    {
        $product_id = $request->product_id;

        $markets = Market::whereIn('id', function ($query) use ($product_id) {
            $query->select('market_id')
                ->from('prices')
                ->where('product_id', $product_id);
        })->get();

        return response()->json($markets);
    }




    public function getChartData(Request $request)
    {
        $product_id = $request->product_id;
        $market_id = $request->market_id;
        $filter = $request->filter ?? 'all';
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Price::where('product_id', $product_id)
                    ->where('market_id', $market_id);

        if ($filter === 'custom' && $startDate && $endDate) {
            // Handle custom date range
            $query->whereBetween('recorded_date', [$startDate, $endDate]);
            $prices = $query->orderBy('recorded_date', 'ASC')->get(['recorded_date', 'price']);
        } else {
            // Predefined filter logic
            switch ($filter) {
                case 'daily':
                    $startDate = now()->subDays(14); // Last 15 days
                    $query->whereBetween('recorded_date', [$startDate, now()]);
                    $prices = $query->orderBy('recorded_date', 'ASC')->get(['recorded_date', 'price']);
                    break;

                case 'weekly':
                    $startDate = now()->subWeeks(14);
                    $prices = $query->selectRaw('YEARWEEK(recorded_date) as week, AVG(price) as avg_price')
                                    ->whereBetween('recorded_date', [$startDate, now()])
                                    ->groupBy('week')
                                    ->orderBy('week', 'ASC')
                                    ->get();
                    break;

                case 'monthly':
                    $startDate = now()->subMonths(14);
                    $prices = $query->selectRaw('DATE_FORMAT(recorded_date, "%Y-%m") as month, AVG(price) as avg_price')
                                    ->whereBetween('recorded_date', [$startDate, now()])
                                    ->groupBy('month')
                                    ->orderBy('month', 'ASC')
                                    ->get();
                    break;

                case 'yearly':
                    $startDate = now()->subYears(14);
                    $prices = $query->selectRaw('YEAR(recorded_date) as year, AVG(price) as avg_price')
                                    ->whereBetween('recorded_date', [$startDate, now()])
                                    ->groupBy('year')
                                    ->orderBy('year', 'ASC')
                                    ->get();
                    break;

                case 'all':
                    $totalRecords = Price::where('product_id', $product_id)
                                        ->where('market_id', $market_id)
                                        ->count();

                    if ($totalRecords <= 15) {
                        // If less than or equal to 15 records, return them all
                        $prices = Price::where('product_id', $product_id)
                                    ->where('market_id', $market_id)
                                    ->orderBy('recorded_date', 'ASC')
                                    ->get(['recorded_date', 'price']);
                    } else {
                        // Select 15 evenly spaced records
                        $interval = ceil($totalRecords / 15);
                        $prices = Price::where('product_id', $product_id)
                                    ->where('market_id', $market_id)
                                    ->orderBy('recorded_date', 'ASC')
                                    ->skip(0)
                                    ->take(15)
                                    ->get(['recorded_date', 'price'])
                                    ->sortBy('recorded_date')
                                    ->values();
                    }
                    break;
            }
        }

        if ($prices->isEmpty()) {
            return response()->json([
                'allDates' => [],
                'values' => []
            ]);
        }

        // Prepare response based on the filter
        $formattedPrices = [];
        $dateLabels = [];

        if (in_array($filter, ['daily', 'all', 'custom'])) {
            foreach ($prices as $price) {
                $dateLabels[] = \Carbon\Carbon::parse($price->recorded_date)->toDateString();
                $formattedPrices[] = $price->price;
            }
        } else {
            foreach ($prices as $price) {
                if ($filter === 'weekly') {
                    $dateLabels[] = 'Week ' . $price->week;
                } elseif ($filter === 'monthly') {
                    $dateLabels[] = $price->month;
                } elseif ($filter === 'yearly') {
                    $dateLabels[] = (string) $price->year;
                }
                $formattedPrices[] = $price->avg_price;
            }
        }

        return response()->json([
            'allDates' => $dateLabels,
            'values' => $formattedPrices
        ]);
    }




    public function show_product($id)
    {
        $product = Product::findOrFail($id); 

        $markets = Market::whereHas('prices', function ($query) use ($product) {
            $query->where('product_id', $product->id);
        })->get();

        return view('ezypay.view_product', compact('product', 'markets'));
    }

    /**
     * Get markets where a specific product is available
     */
    public function getProdMarkets($id)
    {
        $product = Product::findOrFail($id);

        $markets = Market::whereHas('prices', function ($query) use ($product) {
            $query->where('product_id', $product->id);
        })->get();

        return response()->json($markets);
    }

    /**
     * Get product price trends based on filter (daily, weekly, monthly, yearly)
     */
    public function getProdChart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $market_id = $request->market_id;
        $filter = $request->filter ?? 'all';

        $query = Price::where('product_id', $product->id)
                      ->where('market_id', $market_id);

        switch ($filter) {
            case 'daily':
                $startDate = Carbon::now()->subDays(14);
                $prices = $query->whereBetween('recorded_date', [$startDate, now()])
                                ->orderBy('recorded_date', 'ASC')
                                ->get(['recorded_date', 'price']);
                break;

            case 'weekly':
                $startDate = Carbon::now()->subWeeks(14);
                $prices = $query->selectRaw('YEARWEEK(recorded_date) as week, AVG(price) as avg_price')
                                ->whereBetween('recorded_date', [$startDate, now()])
                                ->groupBy('week')
                                ->orderBy('week', 'ASC')
                                ->get();
                break;

            case 'monthly':
                $startDate = Carbon::now()->subMonths(14);
                $prices = $query->selectRaw('DATE_FORMAT(recorded_date, "%Y-%m") as month, AVG(price) as avg_price')
                                ->whereBetween('recorded_date', [$startDate, now()])
                                ->groupBy('month')
                                ->orderBy('month', 'ASC')
                                ->get();
                break;

            case 'yearly':
                $startDate = Carbon::now()->subYears(14);
                $prices = $query->selectRaw('YEAR(recorded_date) as year, AVG(price) as avg_price')
                                ->whereBetween('recorded_date', [$startDate, now()])
                                ->groupBy('year')
                                ->orderBy('year', 'ASC')
                                ->get();
                break;

            case 'all':
                $totalRecords = $query->count();

                $prices = ($totalRecords <= 15)
                    ? $query->orderBy('recorded_date', 'DESC')->get(['recorded_date', 'price'])
                    : $query->orderBy('recorded_date', 'DESC')
                            ->limit(15)
                            ->get(['recorded_date', 'price'])
                            ->sortBy('recorded_date')
                            ->values();
                break;
        }

        if ($prices->isEmpty()) {
            return response()->json(['allDates' => [], 'values' => []]);
        }

        // Format response data
        $dateLabels = [];
        $formattedPrices = [];

        foreach ($prices as $price) {
            $dateLabels[] = match ($filter) {
                'weekly'  => 'Week ' . $price->week,
                'monthly' => $price->month,
                'yearly'  => (string) $price->year,
                default   => Carbon::parse($price->recorded_date)->toDateString()
            };

            $formattedPrices[] = $price->avg_price ?? $price->price;
        }

        return response()->json([
            'allDates' => $dateLabels,
            'values' => $formattedPrices
        ]);
    }











}