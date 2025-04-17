<?php
/**
 * AuthController.php
 * Created By: Josué Saúl Martínez
 * Date: 27/04/2017
 * Time: 01:32
 */
namespace Auth;
use Query;

class AuthController extends \MasterController {
    public function getData(){
        $data['id_user'] = self::httpPost('id');
        $data['password'] = self::httpPost('password');
        return $data;
    }


    public function signIn(){



        // Verifica el usuario //
        $dbdata= null;
        if($this->getSessionInstance('intentos')>=5){
            \Responses::message('Tu sessión está bloqueada intenta más tarde','ERROR');
        }
        if($this->checkClientExist($this->getData()['id_user'],$this->getData()['id_user'])){
            $dbdata= self::model('common_user')->getField('id_user',$this->getData()['id_user']);
        }else{
            \Responses::message('No existe ningun usuario ni correo asociado a  <br>('.$this->getData()['id_user'].')<br> Intente nuevamente corrigiendo sus datos','ERROR');
        }

        // Veriicacion de contraseña //
        if(password_verify($this->getData()['password'],$dbdata[0]['password'])){
            $this->setSessionInstance('session_user_id',$this->getData()['id_user']);
            $this->setSessionInstance('language_selector',\Base::instance()->get('LANGUAGE_SELECTOR'));
            $this->setSessionInstance('session_data_user', Query::qWhere("common_user", "id_user", $this->getData()['id_user']));
            $this->setSessionInstance('auth',$this->getSessionInstance("session_data_user")["id_rol"]);
            \Responses::message('Bienvenido '.$this->getData()['id_user'].' ingresando al panel ','CORRECTO', "","","", Query::qWhere("common_rol", "id_rol",$this->getSessionInstance("auth"))["dir"]);

        }else{
            /***** Attempts Limit *****/
            if($this->getSessionInstance('intentos')<4){
                $this->setSessionInstance('intentos',$this->getSessionInstance('intentos')+1);

                \Responses::message('La contraseña es incorrecta intento '.
                    $this->getSessionInstance('intentos').
                    '<br> Serás bloqueado al quinto intento por 1 hora','ERROR');
            }else {
                $this->setSessionInstance('intentos', $this->getSessionInstance('intentos') + 1);
                $this->setSessionTimer('timer', 'baneo');
                \Responses::message('Has sido Bloqueado' . ($this->
                    getSessionInstance('intentos')), 'ERROR');
            }
        }
    }

    // Cierra y destruye todas las seciones //
    public function signOut(){
        $this->flushSessionAll();
        \Base::instance()->reroute("/login");
    }

    /**
     * @param $field
     * @param $username
     * @return bool
     */
    public function checkUser($field,$username){
        return self::model('common_user')->existValue($field,$username);
    }

    public function checkClientExist($id_user,$email){
        return Query::qMultiWhere("common_user",array("id_user"=> $id_user,"email" => $email),"OR");
    }
}
