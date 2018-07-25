<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Shop\Setting;

class SettingController extends Controller
{

    protected $rules = [
        'key' => 'required',
        'value' => 'required',
        'title' => 'required',
        'type' => 'required',
        'group' => 'required',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Setting::orderBy('key')->paginate();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = $this->rules;
        $setting = new Setting;
        $this->validate($request, $rules);
        $setting->fill($request->all());
        $setting->save();
        return $setting;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $key
     * @return \Illuminate\Http\Response
     */
    public function show($key)
    {
        return Setting::findOrFail($key);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $setting = Setting::whereId($id)->firstOrFail()->toArray();
        return response()->json(['form' => $setting]);
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
        $rules = $this->rules;
        $this->validate($request, $rules);

        $setting = Setting::whereId($id)->firstOrFail();
        $setting->fill($request->all())->save();
        return $setting;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $setting = Setting::whereId($id)->firstOrFail();
        $setting->delete();
    }
}
