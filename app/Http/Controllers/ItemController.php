<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Item;
use App\Raterecord;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('itemratemanage');
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
        try {
            $item = Item::find($request->id);
            $item->delete();
            $request->session()->flash('status', '刪除成功！');
            Redis::del($item->itemname);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function show($id)
    {
        $item = Item::find($id);
        return redirect(['item' => $item]);
    }

    public function edit(Request $request)
    {
        try {
            foreach ($request->temp as $e) {
                $item = Item::find($e[0]);
                $item->update(['itemname' => $e[1], 'rate' => $e[2]]);
            }
            Redis::set('isItemSetyet', false);  //修改redis資料
            
            return null;
        } catch (Exception $e) {
            throw  $e;
        }
    }
    public function validator($data)
    {
        $this->validate($data, [
            'itemname' => 'required|max:15|unique:items',
            'rate' => 'required',
        ]);
    }
    public function getItemName()
    {
        $data = Redis::get('Item');
        $array = json_decode($data, true);
        return $array;
    }
    public function update(Request $request)
    {
        $this->validator($request);

        $item = Item::find($request->id);
        $changed = false;
        try {
            if ($item->rate != $request->rate) {
                $user = Auth::user();
                Raterecord::create(['user_id' => $user->id,'item_id'=>$request->id, 'rate' => $request->rate]);
                Redis::set('isItemSetyet', false);  //修改redis資料
                $changed = true;
            }
            if ($item->itemname != $request->itemname) {
                $item->update(['itemname' => $request->itemname]);
                Redis::set('isItemSetyet', false);  //修改redis資料
                $changed = true;
            }

            if ($changed) {
                $request->session()->flash('status', '修改成功！');
            }

            return $this->index();
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function create(Request $request)
    {
        $this->validator($request);
        $user = Auth::user();
        //未完成功能
        // if ($request->compare == 'totalcompare') {
        // }
        // if ($request->compare == 'onebyonecompare') {
        // }
        //end
        try {
            $item = Item::create($request->all());
            $request->session()->flash('status', '新增成功！');
            Redis::set('isItemSetyet', false); //修改redis資料

            return redirect('item');
        } catch (Exception $e) {
            throw $e;
        }

        return $item;
    }

    public function store()
    {
        $items = Item::all();
        $restult = array();
        $i = 0;
        foreach ($items as $item) {
            $restult[$i] = array($item->id, $item->itemname, $item->rate);
            $i++;
        }

        return $restult;
    }
}
