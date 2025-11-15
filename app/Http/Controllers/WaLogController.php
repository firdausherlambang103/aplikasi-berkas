<?php
namespace App\Http\Controllers;
use App\Models\WaLog;
use Illuminate\Http\Request;

class WaLogController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'berkas_id' => 'required|exists:berkas,id',
            'wa_template_id' => 'required|exists:wa_templates,id',
        ]);

        WaLog::create([
            'berkas_id' => $request->berkas_id,
            'wa_template_id' => $request->wa_template_id,
        ]);

        return response()->json(['success' => true, 'message' => 'Log tersimpan.']);
    }
}