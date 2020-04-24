<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Item;
use App\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\CheckersClass\getItemName;
use App\CheckersClass\getItemRule;
use App\CheckersClass\updateItems;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->middleware('itemRateManage');

        $items = Item::all();

        return view('item.index', [
            'items' => $items
        ]);
    }

    public function destroy(Request $request)
    {
        $this->middleware('itemRateManage');
        $delete = updateItems::getInstance();
        $r = $delete->delete($request->all());
        
        return $r;
    }

    public function show($id)
    {
        $this->middleware('itemRateManage');
        $item = Item::find($id);

        return redirect(['item' => $item]);
    }

    public function edit(Request $request)
    {
        $this->middleware('itemRateManage');
        $edit = updateItems::getInstance();

        return $edit->edit($request);
    }
    public function getItemName()
    {
        $getItemName = getItemName::getInstance();
        
        return $getItemName->getData();
    }
    public function update(Request $request)
    {
        $this->middleware('itemRateManage');
        $update = updateItems::getInstance();
        $update->update($request);
    }
   
    public function create(Request $request)
    {
        $this->middleware('itemRateManage');
        $create = updateItems::getInstance();

        return $create->create($request);
    }


    public function getItemRuleIdName()
    {
        $this->middleware('itemRateManage');
        $getdata = getItemRule::getInstance();

        return $getdata->getItemRuleIdName();
    }

    public function store()
    {
        $this->middleware('itemRateManage');
        $show = updateItems::getInstance();

        return $show->store();
    }
}
