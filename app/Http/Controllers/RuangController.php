<?php

namespace App\Http\Controllers;

use App\Models\KodeRuang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RuangController extends Controller
{
    public function index()
    {
        $ruangs = KodeRuang::where('is_delete', 0)->orderBy('code', 'asc')->get();
        return view('ruang.index', compact('ruangs'));
    }

    public function create()
    {
        return view('ruang.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:tbl_ruang,code|max:255',
            'name' => 'required|unique:tbl_ruang,name|max:255',
        ], [
            'code.required' => 'Kode ruangan harus diisi!',
            'code.unique' => 'Kode ruangan sudah digunakan!',
            'name.required' => 'Nama ruangan harus diisi!',
            'name.unique' => 'Nama sudah digunakan!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // baru lakukan str_pad setelah validasi berhasil
        $data = $request->all();
        $data['code'] = str_pad($request->code, 3, '0', STR_PAD_LEFT);

        KodeRuang::create($data);

        return redirect()->route('ruang.index')->with('success', 'Ruang berhasil ditambahkan.');
    }

    public function edit($code)
    {
        $ruang = KodeRuang::findOrFail($code);
        return view('ruang.edit', compact('ruang'));
    }

    public function update(Request $request, $code)
    {
        $ruang = KodeRuang::findOrFail($code);

        $newCode = str_pad($request->code, 3, '0', STR_PAD_LEFT);
        $data = $request->all();
        $data['code'] = $newCode;

        $validator = Validator::make($data, [
            'code' => 'required|unique:tbl_ruang,code,' . $ruang->getKey() . ',code|max:255',
            'name' => 'required|unique:tbl_ruang,name,' . $ruang->name . ',name|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $ruang->update([
            'code' => $newCode,
            'name' => $request->name,
        ]);

        return redirect()->route('ruang.index')->with('success', 'Ruang berhasil diperbarui.');
    }

    public function show($code)
    {
        $ruang = KodeRuang::findOrFail($code);
        return view('ruang.show', compact('ruang'));
    }
    public function destroy($code)
    {
        try {
            $ruang = KodeRuang::findOrFail($code);
            $ruang->is_delete = 1;
            $ruang->save();
            return redirect()->route('ruang.index')->with('success', 'Ruang berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('ruang.index')->with('error', 'Gagal menghapus ruang.');
        }
    }
}
