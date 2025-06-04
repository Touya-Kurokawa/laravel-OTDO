<?php

namespace App\Http\Controllers;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(){
        $todos = Todo::all();
        return view('todos.index',compact('todos'));// resources/views/todos/index.blade.phpを表示
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|max:255',
        ]);

        Todo::create([
            'title' => $request->title,
            'completed' => false,
        ]);

        return redirect('/todos');
    }
        
    public function toggle (Todo $todo)
    {
        $todo->completed = !$todo->completed;
        $todo->save();

        return redirect('/todos');
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect('/todos');
    }

}
