<?php

namespace App\Http\Controllers;

use App\Models\MyRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MyRecipeController extends Controller
{
    public function index()
    {
        $resep_saya = MyRecipe::where('user_id', Auth::id())->get();
        return view('resep-saya.index', compact('resep_saya'));
    }

    public function create()
{
    return view('resep-saya.create');
}

public function store(Request $request)
{
    $validasi = $request->validate([
        'nama_makanan' => 'required|string|max:255',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'waktu_memasak' => 'nullable|integer',
        'porsi' => 'nullable|integer',
        'jenis_hidangan' => 'nullable|string',
        'bahan' => 'required|array',
        'bahan.*' => 'required|string',
        'instruksi_memasak' => 'required|array',
        'instruksi_memasak.*' => 'required|string'
    ]);

    // Proses gambar jika ada yang diupload
    if ($request->hasFile('gambar')) {
        $path_gambar = $request->file('gambar')->store('resep_saya', 'public');
        $validasi['gambar'] = $path_gambar;
    }

    // Konversi array bahan dan instruksi ke string
    $validasi['bahan'] = implode("\n", $validasi['bahan']);
    $validasi['instruksi_memasak'] = implode("\n", $validasi['instruksi_memasak']);

    // Tambahkan user_id
    $validasi['user_id'] = auth()->id();

    $resep = MyRecipe::create($validasi);

    return redirect()->route('resep-saya.show', $resep->id)
        ->with('success', 'Recipe created successfully');
}

public function show($id)
{
    // Debugging login dan akses
    \Log::error('Debug Akses Resep Detail', [
        'Status Login' => auth()->check() ? 'Sudah Login' : 'Belum Login',
        'ID Pengguna Saat Ini' => auth()->id(),
        'ID Resep yang Dicari' => $id,
    ]);

    // Pastikan pengguna sudah login
    if (!auth()->check()) {
        return redirect()->route('login')
            ->with('error', 'Silakan login terlebih dahulu');
    }

    // Cari resep dan pastikan milik pengguna yang sedang login
    try {
        $resep = MyRecipe::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('resep-saya.show', compact('resep'));
    } catch (\Exception $e) {
        \Log::error('Kesalahan Mengakses Resep', [
            'Pesan' => $e->getMessage(),
            'ID Resep' => $id,
            'ID Pengguna' => auth()->id()
        ]);

        return redirect()->route('resep-saya.index')
            ->with('error', 'Resep tidak ditemukan atau Anda tidak memiliki izin.');
    }
}
public function cekResep($id)
{
    try {
        $resep = MyRecipe::findOrFail($id);
        
        // Log detail resep
        \Log::info('Detail Resep Terperinci', [
            'ID Resep' => $resep->id,
            'Nama Makanan' => $resep->nama_makanan,
            'ID Pembuat' => $resep->user_id,
            'ID Pengguna Saat Ini' => auth()->id(),
            'Semua Atribut' => $resep->toArray()
        ]);

        return response()->json([
            'status' => 'success',
            'resep' => $resep,
            'current_user_id' => auth()->id()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 404);
    }
}
public function destroy($id)
{
    $resep = MyRecipe::findOrFail($id);
    
    // Pastikan hanya pemilik yang bisa hapus
    if ($resep->user_id !== Auth::id()) {
        return redirect()->back()->with('error', 'You do not have permission to delete this recipe');
    }

    // Hapus gambar dari storage jika ada
    if ($resep->gambar) {
        Storage::disk('public')->delete($resep->gambar);
    }

    $resep->delete();

    return redirect()->route('resep-saya.index')
        ->with('status', 'Recipe successfully deleted');
}

public function edit($id)
{
    $resep = MyRecipe::findOrFail($id);

    // Pastikan hanya pemilik resep yang bisa mengedit
    if ($resep->user_id !== auth()->id()) {
        return redirect()->route('resep-saya.index')
            ->with('error', 'Anda tidak memiliki izin mengedit resep ini');
    }

    return view('resep-saya.edit', compact('resep'));
}

public function update(Request $request, $id)
{
    $resep = MyRecipe::findOrFail($id);

    // Pastikan hanya pemilik resep yang bisa mengupdate
    if ($resep->user_id !== auth()->id()) {
        return redirect()->route('resep-saya.index')
            ->with('error', 'Anda tidak memiliki izin mengedit resep ini');
    }

    $validasi = $request->validate([
        'nama_makanan' => 'required|string|max:255',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'waktu_memasak' => 'nullable|integer',
        'porsi' => 'nullable|integer',
        'jenis_hidangan' => 'nullable|string',
        'bahan' => 'required|array',
        'bahan.*' => 'required|string',
        'instruksi_memasak' => 'required|array',
        'instruksi_memasak.*' => 'required|string'
    ]);

    // Proses gambar jika ada yang diupload
    if ($request->hasFile('gambar')) {
        // Hapus gambar lama jika ada
        if ($resep->gambar) {
            Storage::disk('public')->delete($resep->gambar);
        }

        // Simpan gambar baru
        $path_gambar = $request->file('gambar')->store('resep_saya', 'public');
        $validasi['gambar'] = $path_gambar;
    }

    // Konversi array bahan dan instruksi ke string
    $validasi['bahan'] = implode("\n", $validasi['bahan']);
    $validasi['instruksi_memasak'] = implode("\n", $validasi['instruksi_memasak']);

    // Update resep
    $resep->update($validasi);

    // Log untuk debugging
    \Log::info('Resep Diperbarui', [
        'ID Resep' => $resep->id,
        'Nama Makanan' => $resep->nama_makanan,
        'User ID' => auth()->id()
    ]);

    return redirect()->route('resep-saya.index')
        ->with('success', 'Recipe updated successfully');
}
}