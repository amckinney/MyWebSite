<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @file services.php
 * @author Alex McKinney
 * @date 5 June 2015
 * @brief This is the web services file (RESTful)
 */
class Services extends CI_Controller
{
    /**
     * GET method(s) for polls
     */
    public function polls($id = NULL) {
      $this->load->model('poll');
      $method = $this->input->server('REQUEST_METHOD');
      try {
        if ($method == 'POST') {
          $this->poll->create($_POST['title'], $_POST['question']);
          $this->output->set_header("HTTP/1.0 200 OK");
        } else {
          if ($id == NULL) {
            $data = $this->poll->getAllPolls();
          } else {
            $data = $this->poll->readPoll($id);
          }
          $this->output->set_header("HTTP/1.0 200 OK");
          $this->output->set_output($data);
        }
      } catch (Exception $e) {
        $this->output->set_status_header(404, "Not found");
      }
    }

    /**
     * GET, POST, and DELETE method for votes.
     */
    public function votes($pollId, $voteId = NULL) {
      $this->load->model('poll');
      $method = $this->input->server('REQUEST_METHOD');
      try {
        if ($voteId == NULL) {
          $voteArray = $this->poll->getPollAnswersData($pollId, 'votes');
          if ($voteArray == array()) {
            throw new Exception("This Poll does not exist!");
          }
          switch ($method) {
            case 'GET':
              $data = json_encode(array('votes'=>$voteArray));
              $this->output->set_header("HTTP/1.0 200 OK");
              $this->output->set_output($data);
            case 'DELETE':
              $this->poll->resetVotes($pollId);
              $this->output->set_header("HTTP/1.0 200 OK");
          }
        } else {
          if ($method != 'POST') {
            throw new Exception("Not a proper request!");
          }
          $this->poll->vote($pollId, $voteId);
          $this->output->set_header("HTTP/1.0 200 OK");
        }
      } catch (Exception $e) {
        $this->output->set_status_header(404, "Not found");
      }
    }
}
