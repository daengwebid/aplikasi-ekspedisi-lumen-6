<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Fleet;

class FleetsController extends Controller
{
    public function index(Request $request)
    {
        $fleets = Fleet::orderBy('created_at', 'DESC')->when($request->q, function($fleets) {
            $fleets->where('plate_number', $request->plate_number);
        })->paginate(10);
        return response()->json(['status' => 'success', 'data' => $fleets]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'plate_number' => 'required|string|unique:fleets,plate_number',
            'type' => 'required',
            'photo' => 'required|image|mimes:jpg,jpeg,png'
        ]);

        $user = $request->user();
        $file = $request->file('photo');
        $filename = $request->plate_number . '-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move('fleets', $filename);

        Fleet::create([
            'plate_number' => $request->plate_number,
            'type' => $request->type,
            'photo' => $filename,
            'user_id' => $user->id
        ]);
        return response()->json(['status' => 'success']);
    }

    public function edit($id)
    {
        $fleet = Fleet::find($id);
        return response()->json(['status' => 'success', 'data' => $fleet]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'plate_number' => 'required|string|unique:fleets,plate_number,' . $id,
            'type' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $fleet = Fleet::find($id);
        $filename = $fleet->photo; //old name
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = $request->plate_number . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move('fleets', $filename);

            File::delete(base_path('public/fleets/' . $fleet->photo));
        }
        $fleet->update([
            'plate_number' => $request->plate_number,
            'type' => $request->type,
            'photo' => $filename
        ]);
        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $fleet = Fleet::find($id);
        File::delete(base_path('public/fleets/' . $fleet->photo));
        $fleet->delete();
        return response()->json(['status' => 'success']);
    }
}
