<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Models\CashDepart;
use App\Models\Ip;
use App\Models\Manager;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends BaseController
{
//    protected $modal = 'App\Models\CashDepart';
//    protected $resource = 'App\Http\Resources\CashResource';
    public function __construct()
    {
        parent::__construct();
    }

    public function getAdmin()
    {
        $id = Auth::id();
        $cash = CashDepart::where('manager_id', $id)->where('is_deleted', false)->get()->toJson();
        $tax = Tax::where('manager_id', $id)->where('is_deleted', false)->get()->toJson();
        $manager = Manager::where('user_id', $id)->where('is_deleted', false)->get()->toJson();
        $listIp = Ip::where('manager_id', $id)->where('is_deleted', false)->get()->toJson();
        $data = [
            'cash' => $cash,
            'tax' => $tax,
            'manager' => $manager,
            'ip' => $listIp,
        ];

        return $this->sendResponse($data, 200);
    }

    public function postAdmin(Request $request)
    {
        $re = Helper::getRequestJson($request);
        $id = Auth::id();
        $validate = $this->validateForm($re);
        if ($validate){
            return $this->sendError(404, $validate);
        }
        $this->updateTax($id, $re);
        $this->createTax($id, $re);
        $this->updateIp($id, $re);
        $this->updateManager($id, $re);
        $this->updateCash($id, $re);
        $this->createCash($id, $re);
        $msg = [
            'message' => 'success'
        ];
        return $this->sendResponse($msg, 200);
    }

    public function validateForm($data)
    {
        $msg = array();
        //validate tax
        foreach ($data["tax"] as $taxOption) {
            if ($taxOption["start_date_use"] == '') {
                $msg = array_merge($msg, array("start_date_use" => trans('validation.tax_datetime')));
                break;
            }
            if ($taxOption["tax_percent"] == '') {
                $msg = array_merge($msg, array("start_date_use" => trans('validation.tax_percent')));
                break;
            }
        }
        foreach ($data["addTax"] as $taxOption) {
            if ($taxOption["start_date_use"] == '') {
                $msg = array_merge($msg, array("start_date_use" => trans('validation.tax_datetime')));
                break;
            }
            if ($taxOption["tax_percent"] == '') {
                $msg = array_merge($msg, array("start_date_use" => trans('validation.tax_percent')));
                break;
            }
        }
        //validate IP
        foreach ($data['ip'] as $ip) {
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                continue;
            } else {
                $msg = array_merge($msg, array("ip" => trans('validation.ip_address')));
                break;
            }
        }
        //validate cash
        foreach ($data["cash"] as $cashOption) {
            if ($cashOption["name"] == '') {
                $msg = array_merge($msg, array("cash" => trans('validation.cash')));
                break;
            }
        }
        foreach ($data["addCash"] as $cashOption) {
            if ($cashOption["name"] == '') {
                $msg = array_merge($msg, array("cash" => trans('validation.cash')));
                break;
            }
        }

        if (sizeof($msg) == 0){
            return null;
        }else{
            return $msg;
        }

    }

    protected function updateTax($id, $data)
    {
        $count = Tax::where('manager_id', $id)->where('is_deleted', false)->count();
        if ($count === 0) {
            return null;
        }

        foreach ($data["tax"] as $taxOption) {
            $updateTax = Tax::find($taxOption["id"]);
            $updateTax->start_date_use = $taxOption["start_date_use"];
            $updateTax->tax_percent = $taxOption["tax_percent"];
            $updateTax->save();
        }
    }

    protected function createTax($id, $data)
    {
        if (sizeof($data["addTax"]) === 0) {
            return null;
        }
        foreach ($data["addTax"] as $taxOption) {
            $tax = new Tax();
            $tax->start_date_use = $taxOption["start_date_use"];
            $tax->tax_percent = $taxOption["tax_percent"];
            $tax->manager_id = $id;
            $tax->save();
        }
    }

    protected function updateIp($id, $data)
    {
        $count = Ip::where('manager_id', $id)->where('is_deleted', false)->count();
        if ($count == 0) {
            $ip = new Ip();
            $ip->ip_address = json_encode(array("ip" => $data["ip"]));
            $ip->manager_id = $id;
            $ip->save();
        } else {
            $ip = Ip::where('manager_id', $id)->where('is_deleted', false)->first();
            $ip->ip_address = json_encode(array("ip" => $data["ip"]));
            $ip->manager_id = $id;
            $ip->save();
        }
    }

    protected function updateManager($id, $data)
    {
        $count = Manager::where('user_id', $id)->where('is_deleted', false)->count();
        if ($count == 0) {
            $manager = new Manager();
            $manager->user_id = $id;
            $manager->memo = $data["memo"];
            $manager->enable_ip = $data["is_enable"];
            $manager->save();
        } else {
            $manager = Manager::where('user_id', $id)->where('is_deleted', false)->first();
            $manager->memo = $data["memo"];
            $manager->enable_ip = $data["is_enable"];
            $manager->save();
        }
    }

    protected function updateCash($id, $data)
    {
        $count = CashDepart::where('manager_id', $id)->where('is_deleted', false)->count();
        if ($count === 0) {
            return;
        }

        foreach ($data["cash"] as $cashOption) {
            $updateCash = CashDepart::find($cashOption["id"]);
            $updateCash->name = $cashOption["name"];
            $updateCash->save();
        }
    }

    protected function createCash($id, $data)
    {
        if (sizeof($data["addCash"]) === 0) {
            return;
        }
        foreach ($data["addCash"] as $cashOption) {
            $cash = new CashDepart();
            $cash->name = $cashOption["name"];
            $cash->manager_id = $id;
            $cash->is_deleted = false;
            $cash->save();
        }
    }
}
