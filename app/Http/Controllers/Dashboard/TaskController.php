<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();
        return view('dashboard.task.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.task.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'date' => 'required',
            'description' => 'required',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if($request->hasfile('images')){
            foreach($request->file('images') as $image){
                $name = $request->name . '_' . rand('0', '100000') . '_' . $image->getClientOriginalName();
                $image->move(public_path() . '/image/', $name);
                $images_name[] = $name;
            }
        }

        $task_create = new Task();
        $task_create->name = $request->input('name');
        $task_create->date = date("Y-m-d", strtotime($request->input('date')));
        $task_create->description = $request->input('description');
        $task_create->images = json_encode($images_name);
        $task_create->save();

        return redirect()->route('task.index')
                        ->with('success','Task created successfully.');
        
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
        $task = Task::find($id);
        return view('dashboard.task.show',compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);
        return view('dashboard.task.edit', compact('task'));
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
        $request->validate([
            'name' => 'required',
            'date' => 'required',
            'description' => 'required',
        ]);

        if($request->hasfile('images') || !isset($request->selectedimageinput)){
            $request->validate([
                'images' => 'required',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
        }

        if($request->hasfile('images')){
            foreach($request->file('images') as $image){
                $name = $request->name . '_' . rand('0', '100000') . '_' . $image->getClientOriginalName();
                $image->move(public_path() . '/image/', $name);
                $images_name[] = $name;
            }
        }

        if(isset($request->selectedimageinput) && !empty($request->selectedimageinput)){
            foreach($request->selectedimageinput as $fileprevname){
                $images_name[] = $fileprevname;
            }
        }

        $task_update = Task::find($id);
        if(isset($images_name) && !empty($images_name)){
            $finaltaskimages = array_diff(json_decode($task_update->images), $images_name);
            foreach($finaltaskimages as $imagetask){
                if(file_exists(public_path() . '/image/' . $imagetask)){
                    unlink(public_path() . '/image/' . $imagetask);
                }
            }
        }

        $task_update->name = $request->input('name');
        $task_update->date = date("Y-m-d", strtotime($request->input('date')));
        $task_update->description = $request->input('description');
        $task_update->images = json_encode($images_name);
        $task_update->update();

        return redirect()->route('task.index')
                        ->with('success','Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);

        foreach(json_decode($task->images) as $imageitem){
            if(file_exists(public_path() . '/image/' . $imageitem)){
                unlink(public_path() . '/image/' . $imageitem);
            }
        }

        $task->delete();

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]); 
    }
}
