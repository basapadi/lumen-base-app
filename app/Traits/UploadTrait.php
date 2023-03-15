<?php
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;


trait UploadTrait {

    /**
     * @author bachtiarpanjaitan <bachtiarpanjaitan0@gmail.com>
     * @param $req Illuminate\Http\Request;
     * @param $imageAttr string Attribute image request, default: image
     * @param $options array 
     * options available:
     *  - size: array - final size [x,y] default: [300,300]
     *  - path: string - Destination path
     *  - permission: integer - set permission folder of destination path, default: 777,
     *  - rules: string - Laravel validation format
     * @return array Image Attributes
     */
    public function UploadImage(Request $req,string $imageAttr = 'image',array $options){
        $imageProp = [];
        $_imageValidationRules = isset($options['rules'])? $options['rules']:'image|mimes:jpg,png,jpeg,svg|max:1024';
        $_avatarSize = isset($options['size'])? $options['size']:[300,300];
        $path = $options['path'];
        $permission = isset($options['permission'])? $options['permission']: 777;
        $_avatarPath = public_path($path);
        if($req->hasFile($imageAttr)){
            $image = $req->file($imageAttr);
            $imageProp['filename'] = time().'_'.$image->getClientOriginalName();
            $imageProp['extension'] = $image->getClientOriginalExtension();

            //Write Folders if not exists
            if (!file_exists($_avatarPath)) {
                mkdir($_avatarPath, $permission, true);
            }
            $objImage = Image::make($image->path());
            $objImage->resize($_avatarSize[0], $_avatarSize[1])->save($_avatarPath.'/'. $imageProp['filename']);

            $imageProp['path'] = url($path).'/'.$imageProp['filename'];
            return  $imageProp;

        } else return [];
    }
}