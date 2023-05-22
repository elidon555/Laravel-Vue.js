<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\UserDetail;
use App\Traits\ReportTrait;
use Laravel\Cashier\Subscription;

class DashboardController extends Controller
{
    use ReportTrait;

    public function activeSubscribers()
    {
        return Subscription::where('stripe_status','active')->distinct()->count();
    }

    public function activeProducts()
    {
        return Subscription::where('stripe_status','active')->count();
    }

    public function totalIncome()
    {
        $fromDate = $this->getFromDate();
        $query = Payment::query();

        if ($fromDate) {
            $query->where('created_at', '>', $fromDate);
        }
        return round($query->sum('amount'));
    }

    public function subscriptionsByCountry()
    {

        $result = UserDetail::query()->select('country')->withCount(['payments'=>function($query){
            $fromDate = $this->getFromDate();
            if ($fromDate) {
                $query->where('created_at', '>=', $fromDate);
            }
        }])->get();
        $data = [];
        foreach ($result as $userDetail){
            @$data[$userDetail['country']] = [
                'name'=> $userDetail['country'],
                'count' => @$data[$userDetail['country']]['count']+=$userDetail['payments_count']
            ];
        }
        return response(array_values($data));
    }

    public function latestSubscribers()
    {
        $result = Payment::select('user_id')->with(['user'])->groupBy('user_id')->orderBy('created_at','desc')->limit(5)->get();
        return response($result);

    }

    public function latestOrders()
    {
        $query = Payment::with(['user','subscriptionPlan'])->orderBy('created_at')->limit(10)->get();
        return response($query);
    }
}
