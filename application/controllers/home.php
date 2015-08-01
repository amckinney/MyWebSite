<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @file polls.php
 * @author Alex McKinney
 * @date 26 July 2015
 * @brief The Home Page Controller
 */
class Home extends CI_Controller
{
    /**
     * Loads the front angular page
     */
    public function index()
    {
        echo("Heroku is updating...");
        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->view('home');
    }
}
