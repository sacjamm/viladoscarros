<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Lead;

class LeadController extends Controller
{
    public function index()
    {
       return view('admin.kanban'); 
    }
    public function leads()
    {
        $leads = Lead::all();
        return response()->json($leads);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $lead = Lead::create($data);

        return response()->json($lead, 201);
    }

    public function update(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'status' => 'required|string',
        ]);

        $lead->update($data);

        return response()->json($lead);
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return response()->json(null, 204);
    }
}