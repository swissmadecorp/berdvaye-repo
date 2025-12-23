<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageCollection;
use Session;

class ImageCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = ImageCollection::orderBy('order','asc')->get();
        return view('admin.imagecollections',['pagename'=>'Image Collections','images'=>$images]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.imagecollections.create',['pagename'=>'Create Image']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required',
        ]);


        if ($validator->fails()) {
            return back()->withInput($request->all())->withErrors($validator);
        }

        return redirect('admin/imagecollections');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $image = ImageCollection::find($id);
        return view('admin.imagecollections.edit',['pagename'=>'Edit Image','image'=>$image]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required',
        ]);


        if ($validator->fails()) {
            return back()->withInput($request->all())->withErrors($validator);
        }

        $image = ImageCollection::find($id);
        $title=$request['title'];
        $imagelocation = base_path().'/public/uploads/collections/'.$image->location;
        
        $title = strtolower(str_replace(' ','-',$title));
        $extension = substr($image->location,-4);
        $fileName = rand(11111, 99999) . $extension;
        $fileName = $title.'-'.$fileName;
        
        $newimagelocation = str_replace($image->location,$fileName,$imagelocation);

        rename($imagelocation,$newimagelocation);

        $imagelocation = base_path().'/public/uploads/collections/thumbs/'.$image->location;
        $newimagelocation = str_replace($image->location,$fileName,$imagelocation);

        rename($imagelocation,$newimagelocation);
        $image->update([
            'title' => $request['title'],
            'location' => $fileName,
            'order' => $request['order']
        ]);

        return redirect('admin/imagecollections');        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productImage = ImageCollection::find($id);
        $imagelocation = base_path().'/public/uploads/collections/'.$productImage->location ;
        $thumbimagelocation = base_path().'/public/uploads/collections/thumbs/'.$productImage->location ;

        unlink($imagelocation);
        unlink($thumbimagelocation);
        $productImage->delete();

        Session::flash('message', "Successfully deleted product!");
        return redirect('admin/imagecollections');
    }
}
