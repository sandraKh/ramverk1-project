<?php

namespace Anax\Question;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Question extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Question";



    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $questionId;
    public $userId;
    public $title;
    public $text;

    public function findLatest()
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($this->tableName)
                        ->orderBy("Question.questionId DESC")
                        ->limit("3")
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }

    public function findAllOrderBy($orderBy, $limit = 10000)
    {
        $this->checkDb();
        return $this->db->connect()
            ->select()
            ->from($this->tableName)
            ->orderBy($orderBy)
            ->limit($limit)
            ->execute()
            ->fetchAllClass(get_class($this));
    }
}
