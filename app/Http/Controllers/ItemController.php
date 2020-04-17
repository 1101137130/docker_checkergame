<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Item;
use App\Itemrule;
use App\Raterecord;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\CheckersClass\createItemRule;

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
        try {
            $item = Item::find($request->id);
            $item->delete();
            Itemrule::where('item_id', $request->id)->delete();

            $request->session()->flash('status', '刪除成功！');
            Redis::set('isItemSetyet', false);  //修改redis資料
        } catch (Exception $e) {
            throw $e;
        }
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
        try {
            $user = Auth::user();
            foreach ($request->temp as $e) {
                Raterecord::create(['user_id' => $user->id,'item_id'=>$e[0], 'rate' =>$e[2]]);
                $item = Item::find($e[0]);
                $item->update(['itemname' => $e[1], 'rate' => $e[2],'limit_amount'=>$e[3]]);
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
    public function specialValidator($data)
    {
        $this->validate($data, [
            'specialCards1' => 'required',
            'specialCards2' => 'required',
            'specialCards3' => 'required',
        ]);
    }
    public function totalValidator($data)
    {
        $this->validate($data, [
            'operator' => 'required',
            'total' => 'required',
        ]);
    }
    public function singleCompareValidator($data)
    {
        $this->validate($data, [
            'winRequire1' => 'required',
            'winRequire2' => 'required',
            'winRequire3' => 'required',
            'winRequire4' => 'required',
            'winRequire5' => 'required',
        ]);
    }
    public function extendCompareValidator($data)
    {
        $this->validate($data, [
            'selectFirst' => 'required',
            'selectSecond' => 'required',
            'selectThird' => 'required',
        ]);
    }
    public function getItemName()
    {
        $data = Redis::get('Item');
        $data = json_decode($data, true);
        $array = array();

        for ($i = 0;$i<=count($data)-1;$i++) {
            array_push($array, array('id'=>$data[$i]['id'],'itemname'=>$data[$i]['itemname'],));
        }

        return $array;
    }
    public function update(Request $request)
    {
        $this->middleware('itemRateManage');

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
                $this->validator($request);
                $item->update(['itemname' => $request->itemname]);
                Redis::set('isItemSetyet', false);  //修改redis資料
                $changed = true;
            }
            if ($item->limit_amount != $request->limit_amount) {
                $item->update(['limit_amount' => $request->limit_amount]);
                Redis::set('isItemSetyet', false);  //修改redis資料
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
        $this->middleware('itemRateManage');
        $this->validator($request);
        $status = (int)$request->status;
        if ($request->limit_amount == null) {
            $request->limit_amount = 10000000;
        }

        switch ($status) {
            case 1:
            $this->singleCompareValidator($request);
                break;
            case 2:
            $this->specialValidator($request);
                break;
            case 3:
            $this->totalValidator($request);
                break;
            case 4:
            $this->extendCompareValidator($request);
                break;
        }

        try {
            $createItemrule = createItemRule::getInstance();
            $item = Item::create($request->all());
            $createItemrule->create($request, $item->id);
            $request->session()->flash('status', '新增成功！');
            Redis::set('isItemSetyet', false); //修改redis資料

            return redirect('item');
        } catch (Exception $e) {
            return $e;
        }
    }


    public function getItemRuleIdName()
    {
        $this->middleware('itemRateManage');
        $restult = DB::table('itemrules')
            ->join('items', 'items.id', '=', 'itemrules.item_id')
            ->select('itemrules.id', 'items.itemname')
            ->where('itemrules.status', '=', 1)
            ->get();
            
        return $restult;
    }

    public function store()
    {
        $this->middleware('itemRateManage');
        $items = Item::all();
        $restult = array();
        $i = 0;
        foreach ($items as $item) {
            $restult[$i] = array($item->id, $item->itemname, $item->rate,$item->limit_amount);
            $i++;
        }

        return $restult;
    }
}
