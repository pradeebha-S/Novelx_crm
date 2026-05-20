<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;

class HrController extends Controller
{
    public function candidates_form()
    {
        return view('Hr.candidates_form');
    }
    public function candidates()
    {
        return view('Hr.candidates');
    }
    public function view_status($id)
    {
        return view('Hr.view_status',compact('id'));
    }
    public function edit_candidates($id)
    {
        $candidate = Candidate::findOrFail($id);
        return view('Hr.edit_candidates',compact('id','candidate'));
    }
}
