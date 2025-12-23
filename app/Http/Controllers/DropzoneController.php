<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DealersController;
use App\Http\Controllers\ProductRetailController;
use App\Models\ProductImage;
use App\Models\Dealer;
use App\Models\Post;
use App\ImageCollection;
use App\Http\Controllers\PostsController;
use App\Models\Customer;
//use App\Models\Products;
use App\Libs\Imagick;

class DropzoneController extends ProductsController
{
    public function deleteImage(Request $request) {
        
        if ($request->ajax()) {
            $imageId= $request['imageId'];
            $id= $request['id'];
            
            $productImage = \App\Models\ProductImage::where('id',$imageId)->first();
            $imagelocation = base_path().'/public/images/'.$productImage->location ;

            \Storage::delete('/public/images/'.$productImage->location);
            $productImage->delete();
            
            return \Response::json('success', 200);
        }
    }

    public function deleteImageFromProductRetail(Request $request) {
        
        if ($request->ajax()) {
            $id= $request['id'];
            
            $productImage = \App\Models\ProductRetail::find($id);
            //$imagelocation = base_path().'/public/images/'.$productImage->image_location ;

            \Storage::delete('/public/images/'.$productImage->image_location);
            \Storage::delete('/public/images/thumbs/'.$productImage->image_location);
            $productImage->image_location = '';
            $productImage->update();
            
            return \Response::json('success', 200);
        }
    }

    public function deleteImageFromPost(Request $request) {
        if ($request->ajax()) {
            $id= $request['post_id'];
            
            $post = Post::find($id);
            unlink(base_path().'/public/images/posts/'.$post->image);
            unlink(base_path().'/public/images/posts/thumbs/'.$post->image);

            $post->update(['image'=>NULL]);
            return \Response::json('success', 200);
        }
    }

    public function deleteCustomerImage(Request $request) {
        if ($request->ajax()) {
            $id= $request['customer_id'];
            
            $customer = Customer::find($id);
            unlink(base_path().'/public/images/logo/'.$customer->logo);
            unlink(base_path().'/public/images/logo/thumbs/'.$customer->logo);

            $customer->update(['logo'=>NULL]);
            return \Response::json('success', 200);
        }
    }

    public function deleteDealerImage(Request $request) {
        if ($request->ajax()) {
            $id= $request['dealer_id'];
            
            $dealer = Dealer::find($id);
            unlink(base_path().'/public/images/logo/'.$dealer->logo);
            unlink(base_path().'/public/images/logo/thumbs/'.$dealer->logo);

            $dealer->update(['logo'=>NULL]);
            return \Response::json('success', 200);
        }
    }

    public function uploadFiles(Request $request) {
        if ($request->ajax()) {
            $rules = array(
                'file' => 'image|max:10000',
            );
            $position=0;
            
            if (isset($request['_form'])) {
                parse_str($request['_form'],$output);
                $title=$output['title'];
            } else {
                $output = $request;
                $title=$output['title'];
            }

            if (!empty($output['_id']))
                $id = $output['_id'];
            else $id = 0;

            $bladeName='';
            if (!empty($output['blade']))
                $bladeName = $output['blade'];
            else {
                $imageposition =  \App\Models\ProductImage::where('product_id',$id)
                    ->orderByRaw('position desc')
                    ->first();

                if ($imageposition)
                    $position = $imageposition->position+1;
            }
            
            foreach($request->file('file') as $file) {   
                $image = array('file' => $file);  

                $validation = \Validator::make($image, $rules);

                if ($validation->fails()) {
                    return \Response::json([
                        'error' => true,
                        'message' => $validation->messages()->first(),
                        'code' =>400
                    ], 400);
                }  
                $new_id=0;
                $title = strtolower(str_replace([' ','-','.','/','&'],'-',$title));
                $extension = $file->getClientOriginalExtension(); // getting file extension
                $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
                $fileName = $title.'-'.$fileName;
                
                if (!$bladeName) {
                    $new_id=ProductImage::create([
                        'product_id' => $id,
                        'title' => $title,
                        'location' => $fileName,
                        'position' => $position
                    ]);

                    //\Log::info('From Dropzone Controller: ');
                    $upload_success = $file->move(public_path("/images"), $fileName); // uploading file to given path
                    $position = $position + 1;
                } elseif ($bladeName=='imagecollection' || $bladeName=='posts'
                     || $bladeName=='customer' || $bladeName=='dealer' || $bladeName=='productretail') {
                    
                    if ($bladeName=='imagecollection') {
                        $new_id=ImageCollection::create([
                            'title' => $output['title'],
                            'location' => $fileName,
                            'order' => $output['order']
                        ]);
                        $folderName = "/collections";
                        $folderNameThumb = "/thumbs";
                    } elseif ($bladeName=='customer') {
                        if ($id==0) {
                            $new_id=CustomersController::saveCustomer($output);
                        } else {
                            $output['logo'] = $fileName;
                            $new_id=CustomersController::updateCustomer($output,$id);
                            
                        }
                        $folderName = "/logo";
                        $folderNameThumb = "/thumbs";
                    } elseif ($bladeName=='dealer') {
                        $output['logo'] = $fileName;
                        if ($id==0) {
                            $new_id=DealersController::saveDealer($output);
                        } else {
                            $new_id=DealersController::updateDealer($output,$id);
                        }
                        $folderName = "/logo";
                        $folderNameThumb = "/thumbs";
                    } elseif ($bladeName=='productretail') {
                        
                            if ($id==0) {
                                $new_id=ProductRetailController::createProduct($output,$fileName);
                            } else {
                                ProductRetailController::updateProduct($id,$output,$fileName);
                            }
                            $folderName = "";
                            $folderNameThumb = "/thumbs";
                    } else {
                        if ($id==0) {
                            $new_id=Post::create([
                                'title' => $output['title'],
                                'post' => $output['posts'],
                                'image' => $fileName
                            ]);
                        } else {
                            $new_id=Post::find($id);
                            $new_id->update([
                                'title' => $output['title'],
                                'post' => $output['posts'],
                                'image' => $fileName
                            ]);
                            
                        }
                        $folderName = "/posts";
                        $folderNameThumb = "/thumbs";
                    }

                    $upload_success = $file->move(public_path("/images/gallery/thumbail/$folderName"), $fileName); // uploading file to given path
                    
                    $imagelocation = base_path()."/public/images/$folderName".$fileName ;
                    $newimagelocation = base_path()."/public/images/$folderName$folderNameThumb/".$fileName ;
                    //return \Response::json($imagelocation);
                    if (!file_exists(base_path()."/public/images/$folderName$folderNameThumb/")) {
                        mkdir(base_path()."/public/images/$folderName$folderNameThumb/");
                    }
                    list($width, $height, $type, $attr) = getimagesize($imagelocation);
                    
                    if ($width > 600)
                        Imagick::open($imagelocation)->thumb(600, 600)->saveTo($newimagelocation);
                    else Imagick::open($imagelocation)->saveTo($newimagelocation);
                    
                }
            }

            if ($upload_success) {
                    if (is_object($new_id))
                        $new_id=$new_id->id;

                    return \Response::json(array('message'=>'success','id'=>$id), 200);
                } else {
                    return \Response::json(array('message'=>'error'), 400);
            }
        }
    }

}
