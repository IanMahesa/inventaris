<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::where('is_delete', 0)->orderBy('kode', 'asc')->get();
        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:tbl_kategori,kode|max:255',
            'nama' => 'required|unique:tbl_kategori,nama|max:255',
        ], [
            'kode.required' => 'Kode jenis barang harus diisi!',
            'kode.unique' => 'Kode jenis barang sudah digunakan!',
            'nama.required' => 'Nama jenis barang harus diisi!',
            'nama.unique' => 'Nama jenis barang sudah digunakan!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data = $request->all();
        $data['kode'] = str_pad(9000 + $request->kode, 3, '0', STR_PAD_LEFT);

        Kategori::create($data);

        return redirect()->route('kategori.index')->with('success', 'kategori berhasil ditambahkan.');
    }

    public function edit($code)
    {
        $kategori = Kategori::findOrFail($code);
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $kode)
    {
        $kategori = Kategori::findOrFail($kode);

        $newkode = str_pad(9000 + $request->kode, 3, '0', STR_PAD_LEFT);
        $data = $request->all();
        $data['kode'] = $newkode;

        $validator = Validator::make($data, [
            'kode' => 'required|unique:tbl_kategori,kode,' . $kategori->kode . ',kode|max:255',
            'nama' => 'required|unique:tbl_kategori,nama,' . $kategori->kode . ',kode|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $kategori->update([
            'kode' => $newkode,
            'nama' => $request->nama,
        ]);

        return redirect()->route('kategori.index')->with('success', 'kategori berhasil diperbarui.');
    }

    public function show($code)
    {
        $kategori = Kategori::findOrFail($code);
        return view('kategori.show', compact('kategori'));
    }
    public function destroy($kode)
    {
        try {
            $kategori = Kategori::findOrFail($kode);
            $kategori->is_delete = 1;
            $kategori->save();
            return redirect()->route('kategori.index')->with('success', 'kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kategori.index')->with('error', 'Gagal menghapus ruang.');
        }
    }
}
