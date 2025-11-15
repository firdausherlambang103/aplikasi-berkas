<?php

namespace App\Http\Controllers;

use App\Models\WaTemplate;
use Illuminate\Http\Request;

class WaTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all templates, newest first
        $templates = WaTemplate::latest()->get();
        return view('wa_templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Just show the creation form
        return view('wa_templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'nama_template' => 'required|string|max:255',
            'template_text' => 'required|string',
        ]);

        // Create the new template
        WaTemplate::create($request->all());

        return redirect()->route('wa-templates.index')
                         ->with('success', 'Template WhatsApp baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     * (Not typically used in this kind of CRUD, but good to have)
     */
    public function show(WaTemplate $waTemplate)
    {
        // Redirect to edit page
        return redirect()->route('wa-templates.edit', $waTemplate);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WaTemplate $waTemplate)
    {
        // Show the edit form, passing the specific template data
        return view('wa_templates.edit', compact('waTemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WaTemplate $waTemplate)
    {
        // Validate the incoming request
        $request->validate([
            'nama_template' => 'required|string|max:255',
            'template_text' => 'required|string',
        ]);

        // Update the existing template
        $waTemplate->update($request->all());

        return redirect()->route('wa-templates.index')
                         ->with('success', 'Template WhatsApp berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WaTemplate $waTemplate)
    {
        // Delete the template
        // Note: We used onDelete('cascade') in migration, 
        // but it's safer to handle related logs if needed in the future.
        // For now, simple delete is fine.
        $waTemplate->delete();

        return redirect()->route('wa-templates.index')
                         ->with('success', 'Template WhatsApp berhasil dihapus.');
    }
}