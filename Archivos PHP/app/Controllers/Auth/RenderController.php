<?php
/**
 *
 * CONTROLADOR DE RENDERIZACIONES
 * Desde este controlador se llevara acabo
 * las renderizaciones de cada una de las v
 * istas generadas
 *
 * Fecha: 24/08/2017
 * Hora: 04:39 PM
 *
 */

namespace Auth;


class RenderController extends \MasterController
{

    public function login()
    {
        self::render('admin/content/login.html.twig');
    }

    public function index()
    {
        self::render('admin/content/index.html.twig');

    }


}