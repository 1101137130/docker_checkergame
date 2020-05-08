<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Http\Request;
use App\CheckersClass\getItemRule;
use App\CheckersClass\updateItems;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('itemRateManage');
    }

    public function index()
    {

        $items = Item::all();

        return view('item.index', [
            'items' => $items
        ]);
    }

    public function destroy(Request $request)
    {
        $delete = updateItems::getInstance();
        $delete->delete($request->all());
    }

    public function show($id)
    {
        $item = Item::find($id);

        return redirect(['item' => $item]);
    }

    public function edit(Request $request)
    {
        $edit = updateItems::getInstance();

        return $edit->edit($request);
    }

    public function update(Request $request)
    {
        $update = updateItems::getInstance();
        $update->update($request);
    }
   
    public function create(Request $request)
    {
        $create = updateItems::getInstance();

        return $create->create($request);
    }

    public function active(Request $request)
    {
        $active = updateItems::getInstance();
        $active->active($request->id);
    }
    public function getItemRuleIdName()
    {
        $getdata = getItemRule::getInstance();

        return $getdata->getItemRuleIdName();
    }

    public function store()
    {
        $show = updateItems::getInstance();

        return $show->store();
    }
}
