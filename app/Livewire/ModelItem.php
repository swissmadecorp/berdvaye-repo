<?php

namespace App\Livewire;

use Imagick;
use Livewire\Component;
use App\Models\ProductRetail;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

class ModelItem extends Component
{

    use WithFileUploads;

    public $photo;
    public $item = [];
    public int $productId = 0;
    public $created_date;
    public $product = null;

    protected function rules() {
        return [
            'photo' => ['nullable', 'image', 'max:1024'],
            'item.p_model' => ['required'],
            'item.model_name' => ['required'],
            'item.p_retail' => ['required'],
        ];
    }

    protected $messages = [
        'photo' =>'Images highest maximum upload size is 1,024 MB.',
        'item.p_model.required' => 'Model Number is required.',
        'item.model_name.required' => 'Model Name is required.',
        'item.p_retail.required' => 'Retail price is required.',
    ];

    public function clearFields() {

        $columns = $this->clearAllFields();

        $this->productId = 0;
        $this->totalorders = 0;
        $this->photo = null;

        // $this->item = null;
        // $this->reset('item','is_duplicate','images','newprice','status');
        $this->resetValidation();
        $this->reset($columns);

    }

    private function clearAllFields() {
        $columns = [
            'item.id',
            'item.p_model',
            'item.description',
            'item.p_retail',
            'item.total_parts',
            'item.dimensions',
            'item.heighest_serial',
            'item.image_location',
            'item.model_name',
            'item.size',
            'item.weight',
            'item.is_active',
            'created_date',
            'photo'];

        return $columns;
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    protected function adjustImage($filename) {
        $folderNameThumb = "/public/images/gallery//thumbnail/";
        if (!file_exists(base_path()."$folderNameThumb")) {
            mkdir(base_path()."$folderNameThumb", 0777, true);
        }

        $imagelocation = base_path()."$folderNameThumb$filename" ;
        $newimagelocation = base_path().$folderNameThumb.$filename;

        list($width, $height, $type, $attr) = getimagesize($imagelocation);
        $img = new Imagick($imagelocation);
        $img->setImageBackgroundColor('#ffffff');
        $img->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
        $img = $img->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);

        if ($width > 250) {

            $img->setImageFormat ("jpg");
            $img->thumbnailImage(215, 150,true,true);

            $img->writeImage($newimagelocation);
        } else {
            if ($folderNameThumb) {
                $img->writeImage($newimagelocation);
            }
        }

    }

    public function saveProduct() {

        $validatedData = $this->validate();
        // $title = Str::slug($this->item['model_name']);
        $title = $this->item['p_model'];
        // $str = $this->generateRandomString(10);
        $filename = $title ."_thumb.jpg";

        if ($this->photo) {
            $this->photo->storeAs('images', $filename ,'public');
            $imageLocation = base_path()."/storage/app/public/images/";
            File::move($imageLocation.$filename, public_path("/images/gallery/thumbnail/$filename"));

            $this->adjustImage($filename);
            $this->item['image_location'] = $filename;
        } else {
            if (isset($this->item['image_location'])) {
                $filepath = pathinfo($this->item['image_location']);
                $this->item['image_location'] = $filepath['filename'].'.'.$filepath['extension'];
            }
            // dd($filepath);
        }

        $this->item['p_model'] = strtoupper($this->item['p_model']);
        if ($this->productId) {
            $product = ProductRetail::find($this->productId);
            $product->update($this->item);
        } else {
            $product = ProductRetail::create($this->item);
            // $this->postToEbay($this->item);
        }

        $this->dispatch('invoke-model',['msg'=>'Product Saved.','id'=>$product->id]);

        $this->clearFields();
    }

    #[On('edit-item')]
    public function editItem($id) {

        if ($id) {
            $product = ProductRetail::find($id);
            $this->item = $product->toArray();
            // dd($this->item);

            $this->productId = $id;
            $this->model = $product->p_model;

            $this->created_date = $product->created_at;
            $filepath = '/images/gallery/thumbnail/'. strtolower($product->p_model) . '_thumb.jpg';
            if (isset($product->image_location)) {
                if (file_exists(public_path($filepath)))
                    $this->item['image_location'] = $filepath;
                else $this->item['image_location'] = "/images/no-image.jpg";
            } else {
                $this->item['image_location'] = "/images/no-image.jpg";
            }


            $this->item['is_active'] = ($this->item['is_active']==1) ? true : false;

            $this->product = $product;
        }
    }

    public function render()
    {
        return view('livewire.model-item');
    }
}
