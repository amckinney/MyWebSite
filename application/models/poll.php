<?php

class Poll extends CI_Model {
    public $id;
    public $title;
    public $question;
    public $answers;
    public $votes;
    public $answerIds;

    public function __construct() {
        $this->load->database();
    }

    /*
     * Return a Poll object read from the database for the given poll.
     * @param int $id  Id of the poll to be returned.
     * @return a Poll instance
     * @throws a generic exception if no such poll exists in the database.
     */
    public function readPoll($id) {
        $poll = new Poll();
        $query = $this->db->get_where('Polls', array('id'=>$id));
        if ($query->num_rows !== 1) {
            throw new Exception("Poll ID $id not found in Polls database");
        }
        $rows = $query->result();
        $row = $rows[0];
        $poll->load($row);

        $poll->answers = $this->getPollAnswersData($id, 'description');
        $poll->votes = $this->getPollAnswersData($id, 'votes');
        $poll->answerIds = $this->getPollAnswersData($id, 'id');

        return json_encode($poll);
    }

    public function getAllPolls() {
        $rows = $this->db->get('Polls')->result();
        $list = array();
        foreach ($rows as $row) {
            $poll = new Poll();
            $poll->load($row);
            $list[] = $poll;
        }
        return json_encode($list);
    }

    public function getPollAnswersData($pollId, $category) {
      $query = $this->db->get_where('Answers', array('pollId'=>$pollId));
      $list = array();
      $rows = $query->result();
      foreach ($rows as $row) {
        foreach ((array) $row as $field => $value) {
          if ($field == $category) {
            $list[] = $value;
          }
        }
      }
      return $list;
    }

    public function getVoteCount($voteId) {
      $query = $this->db->get_where('Answers', array('id'=>$voteId));
      if ($query->num_rows !== 1) {
          throw new Exception("Answer Id $voteId not found in Answers database");
      }
      $rows = $query->result();
      $row = $rows[0];
      foreach ((array) $row as $field => $value) {
        if ($field == 'votes') {
          $voteCount = $value;
        } else {
          continue;
        }
      }
      return $voteCount;

    }

    /*
     * Creates a poll.
     *
    */
    public function create($title, $question) {
      $data = array(
                  'title'=>$title,
                  'question'=>$question
      );
      $this->db->insert('Polls', $data);
    }

    /*
     * Votes for a poll.
     *
    */
    public function vote($pollId, $voteId) {
      $update = array(
                    'pollId'=>$pollId,
                    'answerId'=>$voteId,
                    'ipAddress'=>$this->input->ip_address()
      );
      $this->db->insert('Votes', $update);

      $oldCount = $this->getVoteCount($voteId);
      $newCount = strval(intval($oldCount) + 1);
      $update = array('votes'=>$newCount);
      $this->db->update('Answers', $update, array('id'=>$voteId));
    }

    /*
     * Resets the votes for a given poll.
     *
    */
    public function resetVotes($pollId) {
      $this->db->delete('Votes', array('pollId'=>$pollId));
      $update = array('votes'=>0);
      $this->db->update('Answers', $update, array('pollId'=>$pollId));
    }

    // Given a row from the database, copy all database column values
    // into 'this', converting column names to fields names by converting
    // first char to lower case.
    private function load($row) {
        foreach ((array) $row as $field => $value) {
            $fieldName = strtolower($field[0]) . substr($field, 1);
            $this->$fieldName = $value;
        }
    }
};
