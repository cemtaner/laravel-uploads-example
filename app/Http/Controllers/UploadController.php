<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Images;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

use Intervention\Image\Facades\Image;

class UploadController extends Controller
{

    public function index(){

        $images = Images::query()->orderBy('id','desc')->get();
        return view('form',[
            'images' => $images,
        ]); 

    }

    public function singleFileUpload(Request $request){

        //--file control, jpg,png,gif MAX 5 MB
        $validator = Validator::make($request->all(),[
            'file' => 'required|file|mimes:jpg,png,gif|max:5120',
        ]);

        if($validator->fails()){
            return 'Files other than jpg, png, gif files are not accepted';
        }

        if($request->hasFile('file')){
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();

            $imagePath = 'images/';

            $file->move(public_path($imagePath),$fileName); 

            Images::query()->create([
                'image_name' => $fileName,
                'image_path' => $imagePath,
            ]);

            return 'image uploaded';

        }

    }

    public function multiFileUpload(Request $request){
        //--dosya kontrol işlemi
        if($request->hasFile('images')){

            foreach($request->file('images') as $file) {
                //--dosyanın ismini belirliyoruz
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

                //--resim yolunu belirliyoruz
                $imagePath = 'images/';

                //-dosyayı kaydetme işlemi
                $file->move(public_path($imagePath), $fileName);

                //-db ye kaydet
                Images::query()->create([
                    'image_name' => $fileName,
                    'image_path' => $imagePath,
                ]);
            }
            
            return 'images uploaded';

        }
    }

    public function cropFileUpload(Request $request){
        //-dosya kontrolü
        if($request->hasFile('file')){
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();

            //--gelen değerleri alma
            $width = $request->width;
            $height = $request->height;

            //--dosya kaydetme işlemi
            $file->move(public_path('images'), $fileName);

            //--resim kırpma işlemi
            $cropImage = Image::make(public_path('images/'. $fileName))->fit($width, $height); 
            
            $imagePath = 'images/'; 

            //-kırpılmış resmi kaydetme
            $cropFileName = 'crop_'.$fileName;
            $cropImage->save(public_path($imagePath . $cropFileName));

            //-db ye kaydet
            Images::query()->create([
                'image_name' => $fileName,
                'image_path' => $imagePath,
            ]);

            return 'images uploaded';

        }
    }

    public function documentFileUpload(Request $request){
        //--file control, jpg,png,gif MAX 2 MB
        if($request->hasFile('file')){
            $file = $request->file('file'); 
            $fileExtension = $file->getClientOriginalExtension(); 

            //--dosya türünü kontrol et
            if(in_array($fileExtension, ['pdf','doc','docx'])){
                $fileSize = $file->getSize();

                //--dosya boyut kontrolü
                $maximumSize = 2 * 1024 * 1024; //--let the maximum be 2mb
            }

            //--dosya kaydetme işlemi
            $destinationFolder = public_path('documents');
            $fileName = uniqid() . '.' . $fileExtension;
            $file->move($destinationFolder, $fileName);

            $filePath = 'documents/'. $fileName;

            //--db kayıt işlemi
            Images::query()->create([
                'image_name' => $fileName,
                'image_path' => $filePath, 
            ]);

            return 'file upload completed';

        }
    }

    public function deleteImage($id) {
        
        $image = Images::find($id);

        if(!$image){
            return 'image not found';
        }

        if(!empty($image->image_name)){
            $imagePath = $image->image_path.$image->image_name;

            if(file_exists($imagePath) && unlink($imagePath)){
                $image->delete();
                return 'image deleted successfully';
            }

        }else{
            $image->delete();
            return 'deleted successfully';
        }
    }
}
