<?php
namespace App\CheckersClass;

use App\Item;
use App\Raterecord;
use Illuminate\Support\Facades\Redis;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ItemController;
use App\Order;
use Illuminate\Support\Facades\Session;

class updateItems extends ItemController
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function validator($data)
    {
        $this->validate($data, [
            'itemname' => 'required|max:15|unique:items',
            'rate' => 'required',
            'limit_amount' => 'required|max:1000000',
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
    public function update($request)
    {
        $item = Item::find($request->id);
        try {
            if ($item->rate != $request->rate) {
                $user = Auth::user();
                Raterecord::create(['user_id' => $user->id,'item_id'=>$request->id, 'rate' => $request->rate]);
            }
            if ($item->itemname != $request->itemname) {
                $this->validator($request);
            }
            $item->update($request->all());
            Redis::set('isItemSetyet', false);  //修改redis資料
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function active($id)
    {
        try {
            $item = Item::find($id);
            $item->update(['status'=>1]);
                
            Redis::set('isItemSetyet', false);  //修改redis資料
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function create($request)
    {
        $this->validator($request);

        $status = (int)$request->status;

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
        $user = Auth::user();
        try {
            $createItemrule = createItemRule::getInstance();
            $item = Item::create($request->all());
            $createItemrule->create($request, $item->id);
            Raterecord::create(['user_id'=>$user->id,'item_id'=>$item->id,'rate'=>$item->rate]);
            $request->session()->flash('status', '新增成功！');
            Redis::set('isItemSetyet', false); //修改redis資料

            return redirect('item');
        } catch (Exception $e) {
            return $e;
        }
    }
    public function edit($request)
    {
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
    public function delete($request)
    {
        try {
            $order = Order::where('item_id', $request['id'])->first();
            $item = Item::find($request['id']);
            if ($order == null) {
                $item->delete();

                return Session::flash('status', '刪除成功！');
            } else {
                $item->update(['status'=>2]);
                
                return Session::flash('status', '無法刪除 因為注單有此資料');
            }
            Redis::set('isItemSetyet', false);  //修改redis資料
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function store()
    {
        $items = Item::all();
        $restult = array();
        $i = 0;
        foreach ($items as $item) {
            $restult[$i] = array($item->id, $item->itemname, $item->rate,$item->limit_amount, $item->status);
            $i++;
        }

        return $restult;
    }
}
