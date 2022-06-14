<?php

namespace App\Http\Controllers\Dashboard;

use App\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $items = Item::all();
            return DataTables::of($items)
                ->addIndexColumn()
                ->escapeColumns([])
                ->toJson();
        }

        return view('dashboard.item.index');
    }

    public function createorupdate(Request $request)
    {
        $formvalidate = [
            'title' => 'required',
            'manufacture_date' => 'required',
            'description' => 'required',
        ];

        if ($request->hasfile('images') || !isset($request->selectedimageinput) || empty($request->itemid)) {
            $formvalidate['images'] = 'required';
            $formvalidate['images.*'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        $request->validate($formvalidate);

        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $image) {
                $name = 'item' . '_' . rand('0', '100000') . '_' . $image->getClientOriginalName();
                $image->move(public_path() . '/image/', $name);
                $images_name[] = $name;
            }
        }

        if (isset($request->selectedimageinput) && !empty($request->selectedimageinput) && !empty($request->itemid)) {
            foreach ($request->selectedimageinput as $fileprevname) {
                $images_name[] = $fileprevname;
            }
        }

        if (isset($images_name) && !empty($request->itemid)) {
            $itemsdata = Item::find($request->itemid);
            $finalitemimages = array_diff(json_decode($itemsdata->images), $images_name);
            foreach ($finalitemimages as $imageitem) {
                if (file_exists(public_path() . '/image/' . $imageitem)) {
                    unlink(public_path() . '/image/' . $imageitem);
                }
            }
        }

        $itemadd_data = [
            'title' => $request->title,
            'description' => $request->description,
            'manufacture_date' => date("Y-m-d", strtotime($request->manufacture_date)),
        ];

        $message = "Item created successfully.";

        if($request->hasfile('images') || !empty($request->itemid)){
            $itemadd_data['images'] = json_encode($images_name);
        }

        if(!empty($request->itemid)){
            $message = "Item updated successfully.";
        }

        $Item = Item::updateOrCreate(
            ['id' => $request->itemid],
            $itemadd_data
            );

        return response()->json(['code' => 200, 'message' => $message, 'data' => $Item], 200);
    }

    public function destroy(Request $request)
    {
        $item = Item::find($request->id);

        foreach(json_decode($item->images) as $imageitem){
            if(file_exists(public_path() . '/image/' . $imageitem)){
                unlink(public_path() . '/image/' . $imageitem);
            }
        }

        $item->delete();

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]); 
    }
}
