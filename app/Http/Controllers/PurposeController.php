<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Models\Purpose;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class PurposeController extends Controller
{
    public function index()
    {
        $purposes = Purpose::paginate(Helpers::pagination_limit());
        return view('admin-views.purpose.index', compact('purposes'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'logo' => 'required',
                'color' => 'required',
            ]);
            $purpose = new Purpose();
            $purpose->title = $request->title;
            $purpose->logo = Helpers::upload('purpose/', 'png', $request->file('logo'));
            $purpose->color = $request->color;
            $purpose->save();

            Toastr::success(translate('Successfully Added!'));
            return back();
        } catch (Exception $e) {
            Toastr::error(translate('failed!'));
        }

    }

    public function edit(Request $request)
    {
        $purpose = Purpose::find($request->id);
        return view('admin-views.purpose.edit', compact('purpose'));
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'color' => 'required',
            ]);
            $purpose = Purpose::find($request->id);
            $purpose->title = $request->title;
            $purpose->logo = $request->has('logo') ? Helpers::update('purpose/', $purpose->logo, 'png', $request->file('logo')) : $purpose->logo;
            $purpose->color = $request->color;
            $purpose->save();

            Toastr::success(translate('Successfully Updated!'));
        } catch (Exception $e) {
            Toastr::error(translate('failed!'));
        }
        return redirect(route('admin.purpose.index'));
    }

    public function delete(Request $request)
    {
        $purpose = Purpose::find($request->id);
        unlink('storage/app/public/purpose/' . $purpose->logo);
        $purpose->delete();

        Toastr::success(translate('Successfully Deleted!'));
        return back();
    }
}
