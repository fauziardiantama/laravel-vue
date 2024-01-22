<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $item = Item::all();
        if ($item->isEmpty()) {
            return response()->json([
                'message' => 'No items found'
            ], 404);
        }
        return response()->json([
            'message' => 'Successfully retrieved items',
            'data' => $item
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $item = new Item();
        $item->name = $request->item['name'];
        $item->description = $request->item['description'];
        $item->save();
        if ($item->save()) {
            return response()->json([
                'message' => 'Successfully created item',
                'data' => $item
            ], 201);
        }
        return response()->json([
            'message' => 'Unable to create item'
        ], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        if ($item) {
            return response()->json([
                'message' => 'Successfully retrieved item',
                'data' => $item
            ], 200);
        }
        return response()->json([
            'message' => 'Unable to retrieve item'
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        if ($item) {
            $item->name = $request->item['name'];
            $item->description = $request->item['description'];
            $item->save();
            if ($item->save()) {
                return response()->json([
                    'message' => 'Successfully updated item',
                    'data' => $item
                ], 200);
            }
            return response()->json([
                'message' => 'Unable to update item'
            ], 400);
        }
        return response()->json([
            'message' => 'Unable to retrieve item'
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        if ($item) {
            $item->delete();
            if ($item->delete()) {
                return response()->json([
                    'message' => 'Successfully deleted item'
                ], 200);
            }
            return response()->json([
                'message' => 'Unable to delete item'
            ], 400);
        }
        return response()->json([
            'message' => 'Unable to retrieve item'
        ], 404);
    }
}
