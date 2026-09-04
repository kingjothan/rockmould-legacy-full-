<?php

namespace App\Http\Controllers;

use App\Models\BrochureDownload;
use App\Models\Project;
use Illuminate\Http\Request;

class BrochureController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
        ]);

        if (! $project->brochure_path) {
            return response()->json(['error' => 'No brochure available.'], 404);
        }

        BrochureDownload::create([
            'project_id' => $project->id,
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'url' => asset('storage/' . $project->brochure_path),
        ]);
    }
}
