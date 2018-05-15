<?php

class SomeController extends Controller
{
    public function upload(){
    // Get form upload file
    $files = request()->file('image');
    Foreach($files as $file){
        // Move to the framework application root directory /uploads/ directory
        $info = $file->move( '../uploads');
        If($info){
            // Get uploaded information after successful upload
            // output jpg
            echo $info->getExtension(); 
            // Output 42a79759f284b767dfcb2a0197904287.jpg
            echo $info->getFilename(); 
        }else{
            // upload failed to get error message
            echo $file->getError();
        }    
    }
}
}
