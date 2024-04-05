<?php


class Controller
{
    //method gọi model
    public function model($model)
    {
        require_once './app/models/' . $model . '.php';
        return new $model;
    }

    //method gọi view
    public function view( $view, $data = [])
    {
        require_once './app/views/pages/' . $view . '.php';
    }
}
