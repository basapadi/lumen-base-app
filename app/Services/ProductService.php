<?php
namespace App\Services;

use App\Libraries\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Traits\{
    StaticResponseTrait,
    UploadTrait
};
use App\Models\{
    Product,
    ProductImage
};
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Statics\ProductTypeStatic;
use stdClass;

class ProductService {

    use StaticResponseTrait,UploadTrait;

    public function create(Request $request){
        $productTypes = [ProductTypeStatic::$SINGLE, ProductTypeStatic::$GROUP];
        $validated = Validator::make($request->all(), [
            'type' => 'required|numeric|in:'.implode(',',$productTypes),
            'code' => 'required|string|unique:products',
            'barcode' => 'required|string|unique:products',
            'sku' => 'required|string|unique:products',
            'name' => 'required|string',
            'unit_id' => 'required|numeric',
            'height' => 'numeric',
            'weight' => 'numeric',
            'width' => 'numeric',
            'description' => 'string',
        ],[
            'required' => ':attribute cannot be null',
            'string' => ':attribute must be a string',
            'numeric' => ':attribute must be a numeric',
            'unique' => 'the :attribute must be unique',
            'type.in' => 'type must be in ('. implode(',',$productTypes).')'
        ]);

        if ($validated->fails()) {
            return $this->response400($validated->errors()->first());
        }
        
        try {
            DB::beginTransaction();

            $product = new Product;
            $product->code = trim($request->code);
            $product->sku = trim($request->sku);
            $product->type = (int) $request->type;
            $product->name = trim($request->name);
            $product->unit_id = (int) $request->unit_id;
            $product->description = $request->description;
            $product->barcode = trim($request->barcode);
            if(!$product->save()){
                return $this->response400('Cannot save product');
            }

            /**
             * Proses Image
             */
            /*$resultImages = [];
            $images = $request->file();
            dd($images);
            return 0;
            foreach ($images as $key => $image) {
                $iKey = `image_`.$key++;
                $options = [
                    'file' => $iKey,
                    'size' => [500,300],
                    'path' => 'uploads/products/images'
                    
                ];
                $resultImage = $this->uploadImage($request,$options);
                if(empty($resultImage)) {
                    DB::rollBack();
                    return $this->response400('Cannot upload image');
                }
                array_push($resultImages, $resultImage);
            }

            foreach ($resultImages as $key => $img) {
                $success = ProductImage::create([
                    'url' => $img['path'],
                    'product_id' => $product->id
                ]);

                if(!$success) {
                    DB::rollBack();
                    return $this->response400('Cannot save product, Product Image not valid');
                }
            }
        */
            DB::commit();
            return ApiResponse::make(true,'Data Inserted',$product);
            
        } catch (\Throwable $th) {
            return $this->response500($th);
        }


    }

    public function edit(Request $request){
        $productTypes = [ProductTypeStatic::$SINGLE, ProductTypeStatic::$GROUP];
        $validated = Validator::make($request->all(), [
            'type' => 'required|numeric|in:'.implode(',',$productTypes),
            'code' => 'required|string|unique:products',
            'barcode' => 'required|string|unique:products',
            'sku' => 'required|string|unique:products',
            'name' => 'required|string',
            'unit_id' => 'required|numeric',
            'height' => 'numeric',
            'weight' => 'numeric',
            'width' => 'numeric',
            'description' => 'string',
        ],[
            'required' => ':attribute cannot be null',
            'string' => ':attribute must be a string',
            'numeric' => ':attribute must be a numeric',
            'unique' => 'the :attribute must be unique',
            'type.in' => 'type must be in ('. implode(',',$productTypes).')'
        ]);

        if ($validated->fails()) {
            return $this->response400($validated->errors()->first());
        }
        
        try {
            DB::beginTransaction();

            $product = new Product;
            /* $product = new Product;
            $product->code = trim($request->code);
            $product->sku = trim($request->sku);
            $product->type = (int) $request->type;
            $product->name = trim($request->name);
            $product->unit_id = (int) $request->unit_id;
            $product->description = $request->description;
            $product->barcode = trim($request->barcode); */
            $update = [
                'code' => trim($request->code),
                'sku' => trim($request->sku),
                'type' => (int)$request->type,
                'name' => trim($request->name),
                'unit_id' => (int)$request->unit_id,
                'description' => $request->description,
                'barcode' => $request->barcode
            ];
            //dd($request->toArray());
            
            if(!$product::where('id',$request->id)->update($update)){
                return $this->response400('Cannot Update product !');
            }

            /**
             * Proses Image
             */
         /* $resultImages = [];
            $images = $request->file();
            dd($images);
            return 0;
            foreach ($images as $key => $image) {
                $iKey = `image_`.$key++;
                $options = [
                    'file' => $iKey,
                    'size' => [500,300],
                    'path' => 'uploads/products/images',
                    'permission' => 777
                ];
                $resultImage = $this->uploadImage($request,$options);
                if(empty($resultImage)) {
                    DB::rollBack();
                    return $this->response400('Cannot upload image');
                }
                array_push($resultImages, $resultImage);
            }

            foreach ($resultImages as $key => $img) {
                $success = ProductImage::create([
                    'url' => $img['path'],
                    'product_id' => $product->id
                ]);

                if(!$success) {
                    DB::rollBack();
                    return $this->response400('Cannot save product, Product Image not valid');
                }
            }
        */
            DB::commit();
            return ApiResponse::make(true,'Data Updated',$update);
            
        } catch (\Throwable $th) {
            return $this->response500($th);
        }


    }

}