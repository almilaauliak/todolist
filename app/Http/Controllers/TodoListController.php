<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TodoListController extends Controller
{
    /**
     * Mengambil semua data tugas dari array dummy + database.
     * @return array
     */
    private function getAllTasks()
    {
        // Data dummy (static array)
        // $arrayTasks = [
        //     ["id" => 1, "title" => "Belajar PHP", "status" => "belum", "from_db" => false],
        //     ["id" => 2, "title" => "Kerjakan Tugas UX", "status" => "selesai", "from_db" => false],
        // ];

        // Data dari database
        $dbTasks = Task::orderBy('id')->get()->map(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'status' => $task->status,
                'from_db' => true
            ];
        })->toArray();

        // Gabungkan
        return array_merge(
            //$arrayTasks, 
             $dbTasks);
    }

    /**
     * Menampilkan halaman utama
     */
    public function index()
    {
        $tasks = $this->getAllTasks();
        return view('todolist', compact('tasks'));
    }

    /**
     * Menyimpan tugas baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:belum,selesai',
        ]);
        Task::create([
            'title' => $request->title,
            'status' => $request->status,
        ]);
        return redirect()->route('todolist.index');
    }

    /**
     * Update status tugas (AJAX dari checkbox)
     */
    public function updateStatus(Request $request, $id)
    {
        $task = Task::find($id);
        if ($task) {
            // Cek dan update status hanya jika value valid
            $status = $request->input('status');
            if (in_array($status, ['belum', 'selesai'])) {
                $task->status = $status;
                $task->save();
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false, 'msg' => 'Status tidak valid'], 400);
        }
        return response()->json(['success' => false], 404);
    }

    /**
     * Edit data tugas
     */
    public function edit(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:belum,selesai',
        ]);
        $task = Task::findOrFail($id);
        $task->title = $request->title;
        $task->status = $request->status;
        $task->save();

        return redirect()->route('todolist.index');
    }

    /**
     * Hapus tugas, bisa dari database ataupun dari array dummy.
     */
    public function destroy($id)
    {
        // Hapus dari database jika ada
        $task = Task::find($id);
        if ($task) {
            $task->delete();
            return redirect()->route('todolist.index');
        }
        // Untuk array dummy (tidak bisa dihapus)
        return redirect()->route('todolist.index')->with('error', 'Tugas array dummy tidak dapat dihapus.');
    }
}
