<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @file polls.php
 * @author Alex McKinney
 * @date 5 June 2015
 * @brief This file simply serves up the original angular frontpage
 */
class Polls extends CI_Controller
{
    /**
     * Loads the front angular page
     */
    public function index()
    {
        echo "Opening polls...";
        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->view('polls');
    }
}
