<?php

/**
 * Created by PhpStorm.
 * User: JMartinez
 * Date: 22/05/2017
 * Time: 05:33 PM
 */
class Validator
{
    /**
     * @var
     */
    private $array;
    private $validator;

    /**
     * ValidatorController constructor.
     * @param $array
     */
    public function __construct($array)
    {
        $this->array = $array;
        $this->validator = new Valitron\Validator($array);
    }


    /**
     * @param $field
     */
    public function validateName($field, $custommmesage)
    {
        $this->validateRequired($field, $custommmesage);
        $this->noSpecials($field);


    }

    /**
     * @param $field
     */
    public function noSpecials($field){
        $this->validator->rule('regex', $field, '/^[0-9a-z|A-ZñÑáéíóúÁÉÍÓÚ#\d_\s\d\w\ ]{4,100}$/i')
            ->message('No se permite el uso de caractéres especiales '.$field
            );

    }

    /**
     * @param $field
     */

    public function validateZipCode($field)
    {
        $this->validator->rule('numeric',$field)->message('El código postal debe ser numérico ');
        $this->validator->rule('lengthBetween',$field,1,5)->message('El codigo postal debe contener 5 digitos');

    }

    /**
     * @param $field
     */
    public function validateEmail($field, $custommesaje)
    {
        $this->validateRequired($field, $custommesaje);
        $this->validator->rule('email',$field)->message('Debes insertar un correo electrónico válido');

    }

    /**
     * @param $field
     * @param $customessage
     */

    public function validateAlpha($field,$customessage)
    {
        $this->validator->rule('alpha',$field)->message($customessage);
    }

    /**
     * @param $field
     * @param $custommessage
     */

    public function validateAlphaNum($field,$custommessage)
    {
        $this->validator->rule('alphaNum',$field)->message($custommessage);
    }

    public function vaidateNumbers($field,$custommessage){
        $this->validator->rule('numeric',$field)->message($custommessage);
    }

    public function validateRequired($field, $custommesage)
    {
        $this->validator->rule("required", $field)->message($custommesage);
    }
    public function diferent($field, $custommessage) {

        $this->validator->rule("min", $field,  1)->message($custommessage);
    }

    /**
     * @return bool
     */

    public function validate()
    {
        return $this->validator->validate();
    }
    /**
     * @return array|bool
     */
    public function getErrors()
    {
        return $this->validator->errors();
    }
}