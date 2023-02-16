<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Product;
use App\Models\Event;

class EventController extends Controller
{
    public function create()
    {
        $sn= intval(parse_url(url()->full(), PHP_URL_QUERY));
        Log::debug("Event::create sn=".$sn);
        return Inertia::render('Event/Create', ['sn' => $sn]);
    }

    public function edit($id)
    {
        Log::debug("Event::edit - " . $id);
        $event= Event::find($id);
        return Inertia::render('Event/Edit', [
            'event' => $event
        ]);
    }

    public function update(Request $request, $id)
    {
        Log::debug("Event::update - " . $id);
        $request->validate([
            'product_id' => 'required|numeric|min:0',
        ]);
        
        Event::find($id)->fill($request->post())->save();

        return redirect()->route('product', $request->post()["product_id"])->with('success','Event has been updated successfully');
    }

    public function destroy($id)
    {
        Log::debug("Event::destroy - " . $id);
        Event::find($id)->delete();
        return back()->withInput();
//        return redirect()->route('product', $pid)->with('success','Techno has been deleted successfully');
    }

    public function store(Request $request)
    {
        Log::debug("Event::store");
        Log::debug($request->post());

        $id= $request->post()["product_id"];
        Event::create(        
            $request->validate([
                'date'          => 'required|date',
                'product_id'    => 'required|numeric|min:0',
                'sn_n'          => 'required|numeric|min:0',
                'sn_m'          => 'required|numeric|min:0',
                'sn_p'          => 'required|numeric|min:0',
                'description'   => '',
                'techno_id'     => 'required|numeric|min:0',
                'status_id'     => 'required|numeric|min:0',
            ])
        );

        return to_route('product', $id)->with('success','Event has been created successfully.');
    }
}
