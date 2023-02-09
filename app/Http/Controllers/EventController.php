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
        Log::debug("Event::create - q=".$sn);
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
            'title' => 'required',
            'order' => 'required|numeric'
        ]);
        
        Event::find($id)->fill($request->post())->save();

        return redirect()->route('product', $request->post()["product_id"])->with('success','Techno has been updated successfully');
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

        $request->validate([
            'product_id' => 'required|numeric|min:0',
        ]);

        $id= $request->post()["product_id"];
        Event::create($request->post());

        return redirect()->route('product', $id)->with('success','Techno has been created successfully.');
    }
}
