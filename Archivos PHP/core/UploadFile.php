<?php

/**
 * Controlador de subida
 * by Eddie
 *
 */
class UploadFile
{

    /**
     * @param null $data
     * @param $expensions
     * @param $maxsize
     * @param $dir
     * @return array
     */
    public static function upload($data = null, $expensions, $maxsize, $dir, $randname = false)
    {
        $request = array();

        $errors = null;


        $file_name = $data['name'];
        $file_size = $data['size'];
        $file_tmp = $data['tmp_name'];

        $value = explode(".", $file_name);
        $file_ext = strtolower(array_pop($value));

        if (in_array($file_ext, $expensions) === false) {
            $errors .= "Extensión no permitida";
        }
        if ($file_size >= $maxsize * 1048576) {
            $errors .= 'El tamaño del archivo debe ser menor a ' . $maxsize . ' MB';
        }


        if($randname == true){
            $file_name = TextGenerator::genCode("8").".".$file_ext;
        }


        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, $dir . "/" . $file_name);
            $request["status"] = 1;
            $request["path"] = $dir . "/" . $file_name;
            $request["message"] = "Se ha cargado correctamente el archivo <br>" . $file_name;
            return $request;
        } else {
            $request["status"] = 0;
            $request["message"] = "Se ha producido un error al cargar el archivo <br><b>$file_name</b><br>" . $errors;
            return $request;
        }
    }

    /**
     * @param $array
     * @return array
     */
    public static function parser(&$array)
    {

        $file_ary = array();
        $file_count = count($array['name']);
        $file_keys = array_keys($array);

        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $array[$key][$i];
            }
        }

        return $file_ary;

    }

    /**
     * @param null $data
     * @param $dir
     */
    public static function delete($data = null, $dir)
    {
        $file_name = $data['name'];
        unlink($dir . "/" . $file_name);
    }

    public static function uploadMultiple($array, $expensions, $maxsize, $dir)
    {
        $files = self::parser($array);
        $error_ex = 0;
        $dirs = array();
        $response = null;
        @mkdir($dir);
        $messages = "";
        foreach ($files as $file) {
            $file_up = self::upload($file, $expensions, $maxsize, $dir);

            $dirs[] = $file_up["path"];
            if ($file_up["status"] == 0) {
                $messages .= "<br>" . $file_up["message"];
                $error_ex = 1;
            }
        }

        if ($error_ex == 1) {
           self::delFolder($dir);
            $response = array("status" => 0, "messages" => $messages);
        } else {
            $response = array("status" => 1, "messages" => null, "dirs" => $dirs);
        }

        return $response;
    }

    public static function delFolder($path)
    {
        $files = @glob($path . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                @unlink($file); // delete file
        }
        @rmdir($path);


    }

}

