<?php

namespace App\Http\Controllers\Home;

use Image;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class PortfolioController extends Controller
{
    public function allPortfolio()
    {
        $portfolio = Portofolio::latest()->get();
        return view('admin.portfolio.portfolio_all', compact('portfolio'));
    }

    public function addPortfolio()
    {
        return view('admin.portfolio.portfolio_add');
    }

    public function storePortfolio(Request $request)
    {
        $request->validate([
            'portfolio_name' => 'required',
            'portfolio_title' => 'required',
            'portfolio_image' => 'required',
        ],[
            'portfolio_name.required' => 'Portfolio Name is Required',
            'portfolio_title.required' => 'Portfolio Title is Required',
        ]);

        $image = $request->file('portfolio_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

            Image::make($image)->resize(1020,519)->save('upload/portfolio/'.$name_gen);
            $save_url = 'upload/portfolio/'.$name_gen;

            Portofolio::insert([
                'portfolio_name' => $request->portfolio_name,
                'portfolio_title' => $request->portfolio_title,
                'portfolio_description' => $request->portfolio_description,
                'portfolio_image' => $save_url,
                'created_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Portfolio inserted Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('all.portfolio')->with($notification); 
    }
    public function editPortfolio($id)
    {
        $portfolio = Portofolio::findOrFail($id);
        return view('admin.portfolio.portfolio_edit', compact('portfolio'));
    }

    public function updatePortfolio(Request $request)
    {
        $portfolio_id = $request->id;
        $portfolio = Portofolio::findOrFail($portfolio_id);

        if($request->file('portfolio_image')) {
            $image = $request->file('portfolio_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

            Image::make($image)->resize(1020,519)->save('upload/portfolio/'.$name_gen);
            $save_url = 'upload/portfolio/'.$name_gen;

            // Hapus gambar lama (jika diperlukan)
            if (file_exists($portfolio->portfolio_image)) {
                unlink($portfolio->portfolio_image);
            }

            Portofolio::findOrFail($portfolio_id)->update([
                'portfolio_name' => $request->portfolio_name,
                'portfolio_title' => $request->portfolio_title,
                'portfolio_description' => $request->portfolio_description,
                'portfolio_image' => $save_url,
            ]);

            $notification = array(
                'message' => 'Portfolio Updated with Image Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('all.portfolio')->with($notification);
            
        } else {
            Portofolio::findOrFail($portfolio_id)->update([
                'portfolio_name' => $request->portfolio_name,
                'portfolio_title' => $request->portfolio_title,
                'portfolio_description' => $request->portfolio_description,
            ]);

            $notification = array(
                'message' => 'Portfolio Updated without Image Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('all.portfolio')->with($notification);

        }
    }

    public function deletePortfolio($id)
    {
        $portfolio = Portofolio::findOrFail($id);
        $img = $portfolio->portfolio_image;
        unlink($img);

        Portofolio::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Portofolio Image Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function portfolioDetails($id)
    {
        $portfolio = Portofolio::findOrFail($id);
        return view('frontend.portfolio_details', compact('portfolio'));
    }

}
