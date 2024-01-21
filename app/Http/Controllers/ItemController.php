<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Carbon;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::orderBy('created_at', 'DESC')->get();
        if (count($items) < 1) {
            return response()->json([
                'message' => 'No items found'
            ], 404);
        }
        return response()->json([
            'message' => 'Successfully retrieved all items',
            'items' => $items
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $item = new Item;
        $item->name = $request->item['name'];
        $item->save();
        if (!$item) {
            return response()->json([
                'message' => 'Error creating item'
            ], 500);
        }
        return response()->json([
            'message' => 'Successfully created new item',
            'item' => $item
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json([
                'message' => 'Item not found'
            ], 404);
        }
        return response()->json([
            'message' => 'Successfully retrieved item',
            'item' => $item
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json([
                'message' => 'Item not found'
            ], 404);
        }
        $item->completed = $request->item['completed'] ? true : false;
        $item->completed_at = $request->item['completed'] ? Carbon::now() : null;
        $item->save();
        if (!$item) {
            return response()->json([
                'message' => 'Error updating item'
            ], 500);
        }
        return response()->json([
            'message' => 'Successfully updated item',
            'item' => $item
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json([
                'message' => 'Item not found'
            ], 404);
        }
        $item->delete();
        if (!$item) {
            return response()->json([
                'message' => 'Error deleting item'
            ], 500);
        }
        return response()->json([
            'message' => 'Successfully deleted item'
        ], 200);
    }
}
