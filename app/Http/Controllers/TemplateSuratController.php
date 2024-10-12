<?php
namespace App\Http\Controllers;

use App\Models\TemplateSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateSuratController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('limit', 5); // Default 5 records per page
        $templates = TemplateSurat::orderBy('created_at', 'desc')->paginate($perPage);

        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Memeriksa role dan mengarahkan ke view yang sesuai
        if ($user->role == 'super_admin') {
            return view('super_admin.template', compact('templates'));
        } elseif ($user->role == 'admin') {
            return view('admin.template', compact('templates'));
        }

    }



    public function create()
    {
        return view('template.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_template' => 'required|string|max:255',
            'isi_surat' => 'required',
        ]);

        TemplateSurat::create($request->all());

        return redirect()->route('template.index')->with('success', 'Template created successfully.');

    }

    public function show($id)
    {
        $template = TemplateSurat::findOrFail($id);
        return view('template.show', compact('template'));
    }

    public function edit($id)
    {
        $template = TemplateSurat::findOrFail($id);

        $user = Auth::user();

        if ($user->role == 'super_admin') {
            return view('super_admin.edit_template', compact('template'));
        } elseif ($user->role == 'admin') {
            return view('admin.edit_template', compact('template'));
        }

        return view('edit_template', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_template' => 'required|string|max:255',
            'isi_surat' => 'required',
        ]);

        $template = TemplateSurat::findOrFail($id);
        $template->update($request->all());

        return redirect()->route('template.index')->with('success', 'Template updated successfully.');
    }

    public function destroy($id)
    {
        $template = TemplateSurat::findOrFail($id);
        $template->delete();

        return redirect()->route('template.index')->with('success', 'Template deleted successfully.');
    }
}
