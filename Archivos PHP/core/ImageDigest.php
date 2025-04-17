<?php

/**
 * User: JMartinez
 * Date: 14/07/2017
 * Time: 09:43 AM
 */
class ImageDigest
{
    /**
     * @param $extension
     * @return null|string
     */

    private static function format($extension)
    {
        $format = $extension;
        if ($extension == "jpg" or $extension == "JPG" or $extension == "JPEG" or $extension == "jpeg") {
            $format = 'jpeg';
        }
        return $format;
    }

    /**
     * @param $image
     * @return mixed
     */
    private static function imageExtension($image)
    {
        return explode(".", $image)[1];
    }

    /**
     * @param $image
     * @return Image
     */
    private static function image($image)
    {
        return new Image($image);

    }

    /**
     * @param $exstension
     * @param $quality
     * @return string
     */
    private static function quality($exstension, $quality)
    {
        $finalquality = $quality;
        if (self::format($exstension) == 'jpeg') {
            return $quality . '0';
        }
        return $finalquality;
    }

    /**
     * @param $imagers
     * @param $path
     * @param $name
     * @param $image
     * @param $quality
     * @return FALSE|int
     */
    private static function save($imagers,$path, $name, $image, $quality)
    {

        Base::instance()->write($path . $name . "." . self::imageExtension($image),
            $imagers->dump(self::format(self::imageExtension($image)),
                self::quality(self::imageExtension($image), $quality)));

        return $name . "." . self::imageExtension($image);

    }

    public function guardarImagen($imagen, $ruta)
    {
        return move_uploaded_file($imagen, $ruta);
    }

    /**
     * @param $image
     * @param $path
     * @param $name
     * @param $quality
     * @return FALSE|int
     */
    public static function invert($image, $path, $name, $quality)
    {

        $imagers = self::image($image);
        $imagers->invert();
        return self::save($imagers,$path, $name, $image, $quality);
    }

    /**
     * @param $image
     * @param $path
     * @param $name
     * @param $quality
     * @return FALSE|int
     */
    public static function compress($image, $path, $name, $quality)
    {
        $imagers =self::image($image);
        return self::save($imagers,$path, $name, $image, $quality);
    }

    /**
     * @param $level
     * @param $image
     * @param $path
     * @param $name
     * @param $quality
     * @return FALSE|int
     */
    public static function brightness($level,$image, $path, $name, $quality)
    {

        $imagers = self::image($image);
        $imagers->brightness($level);
        return self::save($imagers,$path, $name, $image, $quality);
    }

    /**
     * @param $level
     * @param $image
     * @param $path
     * @param $name
     * @param $quality
     * @return FALSE|int
     */
    public static function contrast($level,$image, $path, $name, $quality)
    {

        $imagers = self::image($image);
        $imagers->contrast($level);
        return self::save($imagers,$path, $name, $image, $quality);
    }

    /**
     * @param $image
     * @param $path
     * @param $name
     * @param $quality
     * @return FALSE|int
     */
    public static function grayscale($image, $path, $name, $quality)
    {
        $imagers = self::image($image);
        $imagers->grayscale();
        return self::save($imagers,$path, $name, $image, $quality);
    }

    /**
     * @param $level
     * @param $image
     * @param $path
     * @param $name
     * @param $quality
     * @return FALSE|int
     */
    public static function smooth($level,$image, $path, $name, $quality)
    {

        $imagers= self::image($image);
        $imagers->smooth($level);
        return self::save($imagers,$path, $name, $image, $quality);

    }

    /**
     * @param $image
     * @param $path
     * @param $name
     * @param $quality
     * @return FALSE|int
     */
    public static function sepia($image, $path, $name, $quality)
    {

        $imagers = self::image($image);
        $imagers->sepia();
        return self::save($imagers,$path, $name, $image, $quality);

    }

    /**
     * @param $level
     * @param $image
     * @param $path
     * @param $name
     * @param $quality
     * @return FALSE|int
     */

    public static function pixelate($level,$image, $path, $name, $quality)
    {

        $imagers = self::image($image);
        $imagers->pixelate($level);
        return self::save($imagers,$path, $name, $image, $quality);

    }

    /**
     * @param $image
     * @param $path
     * @param $name
     * @param $quality
     * @return FALSE|int
     */
    public static function sketch($image, $path, $name, $quality)
    {

        $imagers = self::image($image);
        $imagers->sketch();
        return self::save($imagers,$path, $name, $image, $quality);

    }

    /**
     * @param $image
     * @param $path
     * @param $name
     * @param $quality
     * @return FALSE|int
     */
    public static function horizontalFlip($image, $path, $name, $quality)
    {

        $imagers = self::image($image);
        $imagers->hflip();
        return self::save($imagers,$path, $name, $image, $quality);

    }

    /**
     * @param $image
     * @param $path
     * @param $name
     * @param $quality
     * @return FALSE|int
     */

    public static function verticalFlip($image, $path, $name, $quality)
    {

        $imagers = self::image($image);
        $imagers->vflip();
        return self::save($imagers,$path, $name, $image, $quality);

    }

    /**
     * @param $x1
     * @param $y1
     * @param $x2
     * @param $y2
     * @param $image
     * @param $path
     * @param $name
     * @param $quality
     * @return FALSE|int
     */
    public static function cut($x1,$y1,$x2,$y2,$image, $path, $name, $quality)
    {

        $imagers =self::image($image);
        $imagers->crop($x1,$y1,$x2,$y2);
        return self::save($imagers,$path, $name, $image, $quality);
    }

    public static function resize($width,$height,$image, $path, $name, $quality){
        $imagers =self::image($image);
        $imagers->resize($width,$height);
        self::save($imagers,$path, $name, $image, $quality);
    }






}