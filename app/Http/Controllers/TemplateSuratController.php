<?php
namespace App\Http\Controllers;

use App\Models\TemplateSurat;
use Illuminate\Http\Request;

class TemplateSuratController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('limit', 5); // Default 5 records per page
        $templates = TemplateSurat::orderBy('created_at', 'desc')->paginate($perPage);
        return view('super_admin.template', compact('templates'));
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
        return view('super_admin.edit_template', compact('template'));
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
