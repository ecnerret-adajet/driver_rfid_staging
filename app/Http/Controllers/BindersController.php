<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Card;
use App\Rfid;
use App\Binder;
use App\Cardholder;

class BindersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($CardID)
    {
        $binder = Binder::where('card_id',$CardID)->first();
        $card = Card::where('CardID',$CardID)->first();
        $rfids = Rfid::pluck('name','id');
        $cards = Card::pluck('CardNo','CardID');
        $cardholders = Cardholder::pluck('Name','CardholderID');
        return view('binders.create',compact('rfids','cards','card','cardholders','binder'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $CardID)
    {
        $this->validate($request, [
            'rfid_list' => 'required',
        ]);

        $rfid = $request->input('rfid_list');
        $cardholder_id = $request->input('cardholder_list');

        Binder::updateOrCreate([
            'card_id' => $CardID,
        ],[
            'card_id' => $CardID,
            'rfid_id' => $rfid,
            'cardholder_id' => $cardholder_id
        ]);


        //  Update to Card databse
        $card = Card::where('CardID',$CardID)->update([
            'CardholderID' => $request->input('cardholder_list')
        ]);
       

        return redirect('cards');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
