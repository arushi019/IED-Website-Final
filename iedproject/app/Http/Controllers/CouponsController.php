<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;
use App\Hostel;
use DB;

class CouponsController extends Controller
{
    //
	public function index()
	{
		$user = \Auth::user();
		if ($user->email == 'admin@ied.com')
		{
			$coupons = Coupon::all();
			return view('coupons.index', compact('coupons'));
		}
		else
		{
			$id = $user->id;
			$coupon = Coupon::find($id);
			$rollno = $coupon->rollno;
			$hostel = DB::select("select * from hostels where rollno = '$rollno';");
			
			if($hostel== NULL)
			{
				$last= -99;
			}
			else
			{
				end($hostel);
				$last = key($hostel);
			}
			return view('coupons.show', compact('coupon', 'hostel', 'last'));
		}
	}
	
	public function showCoupon(Request $request)
	{
		
		$coupon = Coupon::find($request->id);
		return view('coupons.showCoupon', compact('coupon'));
	}
	
	public function save(Request $request)
	{
		Coupon::where('id','=',$request->id)->update([ 'startbalance' => $request->amount, 'start_date' => $request->sdate]);
	}
	
	
	public function setCardStatus(Request $request)
	{ 
		if($request->blocked == 0)
		{
			$request->blocked = 1;
		}
		else
		{
			$request->blocked = 0;
		}
		Coupon::where('id','=',$request->id)->update([ 'blocked' => $request->blocked]);
		return $this->index();
	}
	
	public function show($coupon)
	{
		$coupon = Coupon::find($coupon);
		$rollno = $coupon->rollno;
		$hostel = DB::select("select * from hostels where rollno = '$rollno';");
		if($hostel== NULL)
		{
			$last= -99;
		}
		else
		{
			end($hostel);
			$last = key($hostel);
		}
		
		return view('coupons.show', compact('coupon','hostel','last'));
	}
	
	public function destroy(Request $request)
	{
		$coupon = Coupon::find($request->id);
		Coupon::findOrFail($coupon->id)->delete();
		return $this->index();
	}
}
