<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\helpers;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $banner = Banner::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $banner = new Banner();
        }


        $banners = $banner->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.banner.index', compact('banners', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'url' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000'
        ]);

        try {
            $banner = new Banner;
            $banner->title = $request->title;
            $banner->url = $request->url;
            $banner->image = $request->has('image') ? Helpers::upload('banner/', 'png', $request->file('image')) : null;
            $banner->status = 1;
            $banner->receiver = $request->has('receiver') ? $request->receiver : null;
            $banner->save();

        } catch(\Exception $e) {
            Toastr::warning('Banner added failed!');
            return back();
        }

        Toastr::success('Banner added successfully!');
        return back();
    }

    public function edit($id)
    {
        $banner = Banner::find($id);
        return view('admin-views.banner.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'url' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|max:10000'
        ]);

        $banner = Banner::find($id);
        $banner->title = $request->title;
        $banner->url = $request->url;
        $banner->image = $request->has('image') ? Helpers::update('banner/', $banner->image, 'png', $request->file('image')) : $banner->image;
        $banner->receiver = $request->has('receiver') ? $request->receiver : $banner->receiver;
        $banner->save();
        Toastr::success('Banner updated successfully!');
        return redirect(route('admin.banner.index'));
    }

    public function status(Request $request)
    {
        $banner = Banner::find($request->id);
        $banner->status = !$banner->status;
        $banner->save();
        Toastr::success('Banner status updated!');
        return back();
    }

    public function delete(Request $request)
    {
        $banner = Banner::find($request->id);
        Helpers::delete('banner/' . $banner['image']);
        $banner->delete();
        Toastr::success('Banner removed!');
        return back();
    }
}
