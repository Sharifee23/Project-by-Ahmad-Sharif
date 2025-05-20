<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\product;
use App\Models\states;
use App\Models\Market;
use App\Models\Price;
use App\Models\Activities;
use App\Models\User;
use App\Models\Temp_record;
use Spatie\Permission\Models\Role;


class HomeController extends Controller
{
    public function index()
    {
        $product = Product::all()->count();
        $market = Market::all()->count();
        $state = states::all()->count();
        $user = User::all()->count();
        
        return view('ezypay.index');
    }

    public function productView()
    {
        $product = Product::all();
        return view('ezypay.product', compact('product'));
    }

    public function addProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            // Create and save new product
            $product = new Product();
            $product->name = $request->name;
            $product->save();

            // Ensure the user is authenticated before logging activity
            $userId = Auth::id(); 
            if (!$userId) {
                return response()->json(['error' => 'User not authenticated'], 403);
            }

            // Log the activity
            $activityInserted = DB::table('activities')->insert([
                'user_id'    => $userId,
                'action'     => 'Added a new product: ' . $product->name,
                'table_name' => 'products',
                'timestamp'  => now(),
            ]);

            if (!$activityInserted) {
                return response()->json(['error' => 'Failed to log activity'], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product added successfully!',
                'product' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function product()
    {
        return view('ezypay.products');
    }

    public function getall()
    {
        $products = Product::all();

        $products = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'can_edit' => auth()->user()->can('product-edit'),
                'can_view' => auth()->user()->can('product-view'),
                'can_delete' => auth()->user()->can('product-delete'),
            ];
        });

        return response()->json([
            'status' => 200,
            'data' => $products,
        ]);
    }


    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
        ]);

        // Check if the user is authenticated
        if (Auth::check()) {
            $userId = Auth::id();

            // Create new product
            $product = Product::create([
                'name' => $request->name
            ]);

            // Log the activity
            DB::table('activities')->insert([
                'user_id' => $userId,
                'action' => "Added new product: '{$product->name}'.",
                'table_name' => 'products',
                'timestamp' => now(),
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Product added successfully!',
            ]);
        }

        // If the user is not authenticated, return an error response
        return response()->json([
            'status' => 401,
            'message' => 'Unauthorized',
        ]);
    }


    public function update(Request $request)
    {
        // Validate input
        $request->validate([
            'id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
        ]);

        // Find the product record
        $product = Product::find($request->id);
        if (!$product) {
            return response()->json(['status' => 404, 'message' => 'Product not found'], 404);
        }

        // Get old product name
        $oldProductName = $product->name;

        // Update the values
        $product->name = $request->name;
        $product->save();

        // Log the activity
        $userId = Auth::id();
        if ($userId) {
            DB::table('activities')->insert([
                'user_id' => $userId,
                'action' => "Updated product ID {$product->id}. Old name: '{$oldProductName}', New name: '{$product->name}'.",
                'table_name' => 'products',
                'timestamp' => now(),
            ]);
        }

        return response()->json(['status' => 200, 'message' => 'Product updated successfully']);
    }



    public function delete(Request $request)
    {
        // Validate input
        $request->validate([
            'id' => 'required|exists:products,id',
        ]);

        // Find the product
        $product = Product::find($request->id);
        if (!$product) {
            return response()->json(['status' => 404, 'message' => 'Product not found'], 404);
        }

        // Get product name before deletion
        $productName = $product->name;

        // Delete the product
        if ($product->delete()) {
            // Log the activity
            $userId = Auth::id();
            if ($userId) {
                DB::table('activities')->insert([
                    'user_id' => $userId,
                    'action' => "Deleted product ID {$product->id}, Name: '{$productName}'.",
                    'table_name' => 'products',
                    'timestamp' => now(),
                ]);
            }

            return response()->json(['status' => 200, 'message' => 'Product deleted successfully.']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Failed to delete product.']);
        }
    }

    public function show_product($id)
    {
        $product = Product::findOrFail($id);
        $market = Market::all();
        return view('ezypay.view_product', compact('product', 'market'));
    }

    public function getAllPRecords($id = null)
    {
        if (!$id) {
            return response()->json(['error' => 'Product ID is required'], 400);
        }

        $product = Product::findOrFail($id);

        $prices = Price::with(['product', 'market'])
            ->where('product_id', $product->id)
            ->get();

        $formattedPrices = $prices->map(function ($price) {
            return [
                'id' => $price->id,
                'product_id' => $price->product ? $price->product->id : null,
                'product_name' => $price->product ? $price->product->name : 'Unknown',
                'market_id' => $price->market ? $price->market->id : null,
                'market_name' => $price->market ? $price->market->name : 'Unknown',
                'price' => $price->price,
                'recorded_date' => $price->recorded_date,
                'can_edit' => auth()->user()->can('record-edit'),
                'can_delete' => auth()->user()->can('record-delete'),
            ];
        });

        return response()->json([
            'status' => 200,
            'data' => $formattedPrices
        ]);
    }



    public function getProductPrices(Request $request, $id)
    {
        try {
            $filter = $request->query('filter', 'all'); // Default to 'all'

            $query = DB::table('markets')
                ->select('markets.name as market', DB::raw('COALESCE(ROUND(AVG(prices.price), 0), 0) as avg_price'))
                ->leftJoin('prices', function ($join) use ($id, $filter) {
                    $join->on('prices.market_id', '=', 'markets.id')
                        ->where('prices.product_id', $id);

                    // Handle date filtering
                    switch ($filter) {
                        case 'daily':
                            $join->whereDate('prices.recorded_date', Carbon::today());
                            break;
                        case 'weekly':
                            $join->whereBetween('prices.recorded_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                            break;
                        case 'monthly':
                            $join->whereBetween('prices.recorded_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                            break;
                        case 'yearly':
                            $join->whereYear('prices.recorded_date', Carbon::now()->year);
                            break;
                    }
                })
                ->groupBy('markets.id', 'markets.name')
                ->get();

            return response()->json($query);
        } catch (\Exception $e) {
            \Log::error('Error fetching product prices: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }



    public function states()
    {
        return view('ezypay.states');
    }

    public function getallstates()
    {
        $states = States::all();

        $states = $states->map(function ($state) {
            return [
                'id' => $state->id,
                'name' => $state->name,
                'can_edit' => auth()->user()->can('state-edit'),
                'can_delete' => auth()->user()->can('state-delete'),
            ];
        });

        return response()->json([
            'status' => 200,
            'data' => $states,
        ]);
    }


    public function add_states(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            // Create and save new state
            $state = States::create(['name' => $request->name]);

            // Log the activity
            $userId = Auth::id();
            if ($userId) {
                DB::table('activities')->insert([
                    'user_id' => $userId,
                    'action' => "Added new state: '{$state->name}'.",
                    'table_name' => 'states',
                    'timestamp' => now(),
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'State added successfully!',
                'state' => $state,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Failed to add state.'], 500);
        }
    }



    public function update_states(Request $request)
    {
        // Validate input
        $request->validate([
            'id' => 'required|exists:states,id',
            'name' => 'required|string|max:255',
        ]);

        // Find the state record
        $state = States::find($request->id);
        if (!$state) {
            return response()->json(['status' => 404, 'message' => 'State not found'], 404);
        }

        // Get old state name
        $oldStateName = $state->name;

        // Update the values
        $state->name = $request->name;
        $state->save();

        // Log the activity
        $userId = Auth::id();
        if ($userId) {
            DB::table('activities')->insert([
                'user_id' => $userId,
                'action' => "Updated state ID {$state->id}. Old name: '{$oldStateName}', New name: '{$state->name}'.",
                'table_name' => 'states',
                'timestamp' => now(),
            ]);
        }

        return response()->json(['status' => 200, 'message' => 'State updated successfully']);
    }


    public function delete_states(Request $request)
    {
        // Validate input
        $request->validate([
            'id' => 'required|exists:states,id',
        ]);

        // Find the state
        $state = States::find($request->id);
        if (!$state) {
            return response()->json(['status' => 404, 'message' => 'State not found'], 404);
        }

        // Get state name before deletion
        $stateName = $state->name;

        // Delete the state
        if ($state->delete()) {
            // Log the activity
            $userId = Auth::id();
            if ($userId) {
                DB::table('activities')->insert([
                    'user_id' => $userId,
                    'action' => "Deleted state ID {$state->id}, Name: '{$stateName}'.",
                    'table_name' => 'states',
                    'timestamp' => now(),
                ]);
            }

            return response()->json(['status' => 200, 'message' => 'State deleted successfully.']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Failed to delete state.']);
        }
    }

    public function markets()
    {
        $states = states::all();
        return view('ezypay.markets', compact('states'));
    }

    public function add_markets(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
        ]);

        try {
            // Create and save the new market
            $market = Market::create([
                'name' => $request->name,
                'state_id' => $request->state_id,
            ]);

            // Log the activity
            $userId = Auth::id();
            if ($userId) {
                DB::table('activities')->insert([
                    'user_id' => $userId,
                    'action' => "Added new market: '{$market->name}', State ID: {$market->state_id}.",
                    'table_name' => 'markets',
                    'timestamp' => now(),
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Market added successfully!',
                'market' => $market,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to add market.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getallmarkets()
    {
        $markets = Market::select('markets.id', 'markets.name', 'states.name as state_name')
            ->join('states', 'markets.state_id', '=', 'states.id')
            ->get();

        $markets = $markets->map(function ($market) {
            return [
                'id' => $market->id,
                'name' => $market->name,
                'state_name' => $market->state_name, // Corrected from $markets->states->state_name
                'can_edit' => auth()->user()->can('market-edit'),
                'can_delete' => auth()->user()->can('market-delete'),
            ];
        });

        return response()->json([
            'status' => 200,
            'data' => $markets,
        ]);
    }



    public function update_markets(Request $request)
    {
        // Validate input
        $request->validate([
            'id' => 'required|exists:markets,id',
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
        ]);

        // Find the market record
        $market = Market::find($request->id);
        if (!$market) {
            return response()->json(['status' => 404, 'message' => 'Market not found'], 404);
        }

        // Get old market name
        $oldMarketName = $market->name;

        // Update the values
        $market->name = $request->name;
        $market->state_id = $request->state_id;
        $market->save();

        // Log the activity
        $userId = Auth::id();
        if ($userId) {
            DB::table('activities')->insert([
                'user_id' => $userId,
                'action' => "Updated market ID {$market->id}. Old name: '{$oldMarketName}', New name: '{$market->name}', State ID: {$market->state_id}.",
                'table_name' => 'markets',
                'timestamp' => now(),
            ]);
        }

        return response()->json(['status' => 200, 'message' => 'Market updated successfully']);
    }


    public function delete_markets(Request $request)
    {
        // Find the market
        $market = Market::find($request->id);

        if ($market) {
            $marketName = $market->name; // Store name before deletion
            $stateId = $market->state_id;

            if ($market->delete()) {
                // Log the activity
                $userId = Auth::id();
                if ($userId) {
                    DB::table('activities')->insert([
                        'user_id' => $userId,
                        'action' => "Deleted market: '{$marketName}', State ID: {$stateId}.",
                        'table_name' => 'markets',
                        'timestamp' => now(),
                    ]);
                }

                return response()->json(['status' => 200, 'message' => 'Market deleted successfully.']);
            }
        }

        return response()->json(['status' => 400, 'message' => 'Failed to delete market.']);
    }

    public function prices()
    {
        $product = product::all();
        $market = Market::all();
        return view('ezypay.prices', compact('product', 'market'));
    }

    public function add_prices(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'market_id' => 'required|integer|exists:markets,id',
            'price' => 'required|numeric',
        ]);

        // Check if the user is authenticated
        if (Auth::check()) {
            $userId = Auth::id();

            // Retrieve product and market names
            $product = Product::find($request->product_id);
            $market = Market::find($request->market_id);

            if (!$product || !$market) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Product or Market not found',
                ]);
            }

            // Prepare data for insertion
            $priceData = [
                'product_id' => $request->product_id,
                'market_id' => $request->market_id,
                'price' => $request->price,
            ];

            // Create a new price record
            $price = Price::create($priceData);

            // Log the activity
            DB::table('activities')->insert([
                'user_id' => $userId,
                'action' => "Added price for '{$product->name}' in '{$market->name}' at ₦{$request->price}.",
                'table_name' => 'prices',
                'timestamp' => now(),
            ]);

            // Return success response
            return response()->json([
                'status' => 200,
                'message' => 'Price added successfully',
            ]);
        }

        // If the user is not authenticated, return an error response
        return response()->json([
            'status' => 401,
            'message' => 'Unauthorized',
        ]);
    }


    public function addRecord()
    {
        $product = product::all();
        $market = Market::all();
        return view('ezypay.add_record', compact('product', 'market'));
    }

    public function store_record(Request $request)
    {
        $request->validate([
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'market_id' => 'required|array',
            'market_id.*' => 'required|exists:markets,id',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:0',
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['error' => 'User not authenticated'], 403);
        }

        $records = [];
        foreach ($request->product_id as $key => $productId) {
            Temp_record::create([
                'product_id' => $productId,
                'market_id' => $request->market_id[$key],
                'price' => $request->price[$key],
            ]);

            // Collect activity log details
            $records[] = [
                'user_id' => $userId,
                'action' => "Added a price record for Product ID: $productId in Market ID: " . $request->market_id[$key] . " with price: " . $request->price[$key],
                'table_name' => 'temp-record',
                'timestamp' => now(),
            ];
        }

        // Bulk insert activities
        if (!empty($records)) {
            DB::table('activities')->insert($records);
        }

        return response()->json(['message' => 'Records added successfully'], 200);
    }


    public function addToPrices(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'market_id' => 'required|exists:markets,id',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            // Begin transaction
            DB::beginTransaction();

            // Create and save the new price record
            $record = Price::create([
                'product_id' => $request->product_id,
                'market_id' => $request->market_id,
                'price' => $request->price,
                'recorded_date' => $request->recorded_date,
            ]);

            // Delete the corresponding record from temp_records
            DB::table('temp_records')->where('id', $request->id)->delete();

            // Log the activity
            $userId = Auth::id();
            if ($userId) {
                DB::table('activities')->insert([
                    'user_id' => $userId,
                    'action' => "Added a price record for Product ID: " . $request->product_id . 
                                " in Market ID: " . $request->market_id . 
                                " with price: " . $request->price,
                    'table_name' => 'Prices',
                    'timestamp' => now(),
                ]);
            }

            // Commit transaction
            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Record added successfully!',
                'market' => $record,
            ]);
        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Failed to add market.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getAllPendingRecords()
    {
        // Eager load product and market relationships
        $temp_record = Temp_record::with(['product', 'market'])->get();

        // Format the data
        $formattedTemp = $temp_record->map(function ($temp_record) {
            return [
                'id' => $temp_record->id,
                'product_id' => $temp_record->product ? $temp_record->product->id : null, // Null check
                'product_name' => $temp_record->product ? $temp_record->product->name : 'Unknown',
                'market_id' => $temp_record->market ? $temp_record->market->id : null, // Null check
                'market_name' => $temp_record->market ? $temp_record->market->name : 'Unknown',
                'price' => $temp_record->price,
                'recorded_date' => $temp_record->recorded_date,
            ];
        });

        return response()->json([
            'status' => 200,
            'data' => $formattedTemp
        ]);
    }



    public function getAllRecords()
    {
        // Eager load product and market relationships
        $prices = Price::with(['product', 'market'])->get();

        // Format the data
        $formattedPrices = $prices->map(function ($price) {
            return [
                'id' => $price->id,
                'product_id' => $price->product ? $price->product->id : null, // Null check
                'product_name' => $price->product ? $price->product->name : 'Unknown',
                'market_id' => $price->market ? $price->market->id : null, // Null check
                'market_name' => $price->market ? $price->market->name : 'Unknown',
                'price' => $price->price,
                'recorded_date' => $price->recorded_date,
                'can_edit' => auth()->user()->can('record-edit'), // Permission for edit
                'can_delete' => auth()->user()->can('record-delete'), // Permission for delete
            ];
        });

        return response()->json([
            'status' => 200,
            'data' => $formattedPrices
        ]);
    }



    public function update_records(Request $request)
    {
        // Validate input
        $request->validate([
            'id' => 'required|exists:prices,id',
            'product_id' => 'required|exists:products,id',
            'market_id' => 'required|exists:markets,id',
            'price' => 'required|numeric|min:0',
        ]);

        // Find the record to update
        $price = Price::find($request->id);
        if (!$price) {
            toastr()->error('Record not found!');
            return redirect()->back();
        }

        // Get old values (including names)
        $oldProductName = Product::find($price->product_id)->name ?? 'Unknown';
        $oldMarketName = Market::find($price->market_id)->name ?? 'Unknown';

        // Get new values (including names)
        $newProductName = Product::find($request->product_id)->name ?? 'Unknown';
        $newMarketName = Market::find($request->market_id)->name ?? 'Unknown';

        // Update the values
        $price->product_id = $request->product_id;
        $price->market_id = $request->market_id;
        $price->price = $request->price;
        $price->recorded_date = $request->recorded_date ?? now();
        $price->save();

        // Log the update activity
        $userId = Auth::id();
        if (!$userId) {
            toastr()->error('User not authenticated!');
            return redirect()->back();
        }

        DB::table('activities')->insert([
            'user_id' => $userId,
            'action'  => "Updated price record ID {$price->id}. Old values: Product '{$oldProductName}', Market '{$oldMarketName}', Price {$price->price}. New values: Product '{$newProductName}', Market '{$newMarketName}', Price {$price->price}.",
            'table_name' => 'prices',
            'timestamp'  => now(),
        ]);

        toastr()->success('Record updated successfully!');
        return redirect()->back();
    }

    public function delete_records(Request $request)
    {
        // Validate input
        $request->validate([
            'id' => 'required|exists:prices,id',
        ]);

        // Find the state
        $record = Price::find($request->id);
        if (!$record) {
            return response()->json(['status' => 404, 'message' => 'Record not found'], 404);
        }

        // Get state name before deletion
        $recordName = $record->name;

        // Delete the state
        if ($record->delete()) {
            // Log the activity
            $userId = Auth::id();
            if ($userId) {
                DB::table('activities')->insert([
                    'user_id' => $userId,
                    'action' => "Deleted record ID {$record->id}, Name: '{$recordName}'.",
                    'table_name' => 'prices',
                    'timestamp' => now(),
                ]);
            }

            return response()->json(['status' => 200, 'message' => 'Record deleted successfully.']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Failed to delete Record.']);
        }
    }


    public function users() {
        $roles = Role::all();
        return view('ezypay.user_management', compact('roles'));
    }

    public function getAllUsers()
    {
        $users = User::with('roles')->get(); // Fetch users with their roles

        return response()->json([
            'status' => 200,
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name')->toArray(), // Get role names as an array
                    'can_edit' => auth()->user()->can('user-edit'),
                    'can_delete' => auth()->user()->can('user-delete'),
                ];
            })
        ]);
    }



    public function addUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        
        $roles = $request->roles ?? [];
        $user->syncRoles($roles);
        

        return response()->json([
            'status' => 200,
            'message' => 'User registered successfully',
        ]);
    }


    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('ezypay.edit_user', compact('user', 'roles'));
    }

    public function viewUser($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return view('ezypay.view_user', compact('user'));
    }

    public function getUserActivities($id)
    {
        // Fetch activities for the specified user
        $activities = Activities::where('user_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        $formattedActivities = $activities->map(function ($activity) {
            return [
                'id'     => 'EPMS' . $activity->id,
                'action' => $activity->action,
                'table'  => $activity->table_name,
                'date'   => $activity->timestamp,
            ];
        });

        // Return JSON response
        return response()->json([
            'status' => 200,
            'data'   => $formattedActivities
        ]);
    }


    public function updateUser(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'roles' => 'required|array',
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        // Update user details
        $user->name = $request->name;
        $user->email = $request->email;

        // Only update the password if a new one is provided
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Sync roles (assuming you're using Spatie Laravel Roles & Permissions)
        $user->syncRoles($request->roles);

        // Save the updated user
        $user->save();

        // Redirect with success message
        return redirect()->route('users')->with('success', 'User updated successfully!');
    }

    public function deleteUser(Request $request)
    {
        // Validate input
        $request->validate([
            'id' => 'required|exists:prices,id',
        ]);

        // Find the state
        $user = User::find($request->id);
        if (!$user) {
            return response()->json(['status' => 404, 'message' => 'User not found'], 404);
        }

        // Get state name before deletion
        $userName = $user->name;

        // Delete the state
        if ($user->delete()) {
            // Log the activity
            $userId = Auth::id();
            if ($userId) {
                DB::table('activities')->insert([
                    'user_id' => $userId,
                    'action' => "Deleted record ID {$user->id}, Name: '{$userName}'.",
                    'table_name' => 'Users',
                    'timestamp' => now(),
                ]);
            }

            return response()->json(['status' => 200, 'message' => 'User deleted successfully.']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Failed to delete User.']);
        }
    }

    public function activities() 
    {
        return view('ezypay.activities');
    }

    
    public function getActivities()
    {
        // Fetch activities ordered by timestamp in descending order (latest first)
        $activities = Activities::with(['user'])->orderBy('id', 'desc')->get();

        // Format the data
        $formattedActivities = $activities->map(function ($activity) {
            return [
                'id' => 'EPMS' . $activity->id,
                'user_id' => $activity->user->id, 
                'user_name' => $activity->user->name,
                'action' => $activity->action,   
                'table' => $activity->table_name,
                'date' => $activity->timestamp,
            ];
        });

        // Return JSON response
        return response()->json([
            'status' => 200,
            'data' => $formattedActivities
        ]);
    }






    public function getProducts()
    {
        $products = product::all(); // Fetch all products
        return response()->json(['products' => $products]);
    }

    public function getMarkets()
    {
        $markets = Market::all(); // Fetch all markets
        return response()->json(['markets' => $markets]);
    }

    public function getStates()
    {
        $states = states::all(); // Fetch all states
        return response()->json(['states' => $states]);
    }


    






    //lineChart










}
