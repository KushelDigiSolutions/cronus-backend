<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Admin\Page;
use App\Models\Admin\Footer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{

    public function uplodd(Request $request)
    {

        /*
        This script is used by image upload input to save the image on the server and return the image url to be set as image src attribute.
        */

        $uploadDenyExtensions = ['php'];
        $uploadAllowExtensions = ['ico', 'jpg', 'jpeg', 'png', 'gif', 'webp'];

        function showError($error)
        {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            die($error);
        }

        function sanitizeFileName($file)
        {
            //sanitize, remove double dot .. and remove get parameters if any
            $file = preg_replace('@\?.*$@', '', preg_replace('@\.{2,}@', '', preg_replace('@[^\/\\a-zA-Z0-9\-\._]@', '', $file)));
            return $file;
        }


        define('UPLOAD_FOLDER', __DIR__ . '/');
        if (isset($_POST['mediaPath'])) {
            define('UPLOAD_PATH', sanitizeFileName($_POST['mediaPath']) . '/');
        } else {
            define('UPLOAD_PATH', '/');
        }

        $fileName = $_FILES['file']['name'];
        $extension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));

        //check if extension is on deny list
        if (in_array($extension, $uploadDenyExtensions)) {
            showError("File type $extension not allowed!");
        }

        /*
        //comment deny code above and uncomment this code to change to a more restrictive allowed list
        // check if extension is on allow list
        if (!in_array($extension, $uploadAllowExtensions)) {
            showError("File type $extension not allowed!");
        }
        */

        $destination = './backend/admin/media' . '/' . $fileName;
        move_uploaded_file($_FILES['file']['tmp_name'], $destination);

        if (isset($_POST['onlyFilename'])) {
            echo $fileName;
        } else {
            echo UPLOAD_PATH . $fileName;
        }

    }

    public function savee(Request $request, $id)
    {
        $dataa = Page::find($request->id);
        $dataa->html = $request->html;
        $dataa->save();
        echo "File saved '$dataa->title'";
    }
}
