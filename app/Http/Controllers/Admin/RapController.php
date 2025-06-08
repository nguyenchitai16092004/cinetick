<?php

namespace App\Http\Controllers\Admin;

use App\Models\Rap;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RapController extends Controller
{
    public function index()
    {
        $raps = Rap::all();
        return view('backend.pages.rap.rap', compact('raps'));
    }

    public function create()
    {
        return view('backend.pages.rap.quan-ly-rap');
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenRap' => 'required|max:100',
            'DiaChi' => 'required|max:255',
            'TrangThai' => 'required|max:50',
        ]);

        Rap::create($request->all());
        return redirect()->route('rap.index')->with('success', 'Rạp đã được thêm thành công');
    }

    public function edit($id)
    {
        $rap = Rap::where('ID_Rap', '=', $id)->get();
        return view('backend.pages.rap.quan-ly-rap', compact('rap'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'TenRap' => 'required|max:100',
            'DiaChi' => 'required|max:255',
            'TrangThai' => 'required|max:50',
        ]);

        $rap = Rap::where('ID_Rap', '=', $id)->first();
        $rap->update($request->all());

        return redirect()->route('rap.index')->with('success', 'Rạp đã sửa thêm thành công');
    }

    public function destroy($id)
    {
        $rap = Rap::where('ID_Rap', '=', $id);
        $rap->delete();

        return redirect()->route('rap.index')->with('success', 'Rạp đã được xóa thành công');
    }
}