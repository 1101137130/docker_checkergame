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

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->middleware('itemratemanage');

        $items = Item::all();

        return view('item.index', [
            'items' => $items
        ]);
    }

    public function destroy(Request $request)
    {
        $this->middleware('itemratemanage');
        try {
            $item = Item::find($request->id);
            $item->delete();
            $itemrule = Itemrule::where('item_id', $request->id)->delete();

            $request->session()->flash('status', '刪除成功！');
            Redis::set('isItemSetyet', false);  //修改redis資料
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function show($id)
    {
        $this->middleware('itemratemanage');
        $item = Item::find($id);
        return redirect(['item' => $item]);
    }

    public function edit(Request $request)
    {
        $this->middleware('itemratemanage');
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
    public function getItemName()
    {
        $data = Redis::get('Item');
        $data = json_decode($data, true);
        $array = array();

        for ($i = 0;$i<=count($data)-1;$i++) {
            array_push($array, array('id'=>$data[$i]['id'],'itemname'=>$data[$i]['itemname']));
        }
        return $array;
    }
    public function update(Request $request)
    {
        $this->middleware('itemratemanage');

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
        $this->middleware('itemratemanage');
        $this->validator($request);

        try {
            $special_cards = null;
            $one = null;
            $two  = null;
            $three  = null;
            $four  = null;
            $five  = null;
            $operator = null;
            $total = null;

            if ($request->special != null) {
                $this->specialValidator($request);
                $arrayCards = [];
                array_push($arrayCards, $request->specialCards1);
                array_push($arrayCards, $request->specialCards2);
                array_push($arrayCards, $request->specialCards3);
                
                $special_cards = $this->dataConverter($arrayCards);
                $status = 2;
            }

            if ($request->singleCompare != null) {
                $this->singleCompareValidator($request);
                $one = $this->dataConverter($request->winRequire1);
                $two = $this->dataConverter($request->winRequire2);
                $three = $this->dataConverter($request->winRequire3);
                $four = $this->dataConverter($request->winRequire4);
                $five = $this->dataConverter($request->winRequire5);
                $status = 1;
            }
           
            if ($request->total != null) {
                $this->totalValidator($request);
                $operator = $request->operator;
                $total = $request->total;
                $status = 3;
            }
           
            $item = Item::create($request->all());
            Itemrule::create([
            'item_id'=>$item->id,
            'special_cards'=>$special_cards,
            'one'=>$one,
            'two'=>$two,
            'three'=>$three,
            'four'=>$four,
            'five'=>$five,
            'operator'=>$operator,
            'total'=>$total,
            'status'=>$status
            ]);
            $request->session()->flash('status', '新增成功！');
            Redis::set('isItemSetyet', false); //修改redis資料

            return redirect('item');
        } catch (Exception $e) {
            throw $e;
        }

        return $this->index();
    }
    public function dataConverter($data)
    {
        $temp =1;
        $result =0;
       
        for ($i = sizeof($data)-1 ; $i>=0 ; $i--) {
            $result=$result+(int)$data[$i]*$temp;
            $temp=$temp*10;
        }
        
        return $result;
    }

    public function getItemRuleIdName()
    {
        $this->middleware('itemratemanage');
        $restult = DB::table('itemrules')
            ->join('items', 'items.id', '=', 'itemrules.item_id')
            ->select('itemrules.id', 'items.itemname')
            ->where('itemrules.status','=',1)
            ->get();
            
        return $restult;
    }

    public function store()
    {
        $this->middleware('itemratemanage');
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
