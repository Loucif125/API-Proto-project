<?php

/**
 * Class UserModel
 */
class TaskListModel implements Serializable
{

    private $userId = 0;
    private $tasklistId = 0;
    private $title = null;

    public function serialize() {
        return array(
                'user_id' => $this->userId,
                'tasklist_id' => $this->tasklistId,
                'title' => $this->title
            );
    }
    public function unserialize($pdoResults) {
        $this->setUserId($pdoResults['user_id']);
        $this->setTasklistId($pdoResults['tasklist_id']);
        $this->setTitle($pdoResults['title']);
    }

    public function setTaskList($listId, $userId, $title)
    {
        $this->setUserId($userId);
        $this->setTasklistId($listId);
        $this->setTitle($title);
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getTasklistId()
    {
        return $this->tasklistId;
    }

    /**
     * @param int $tasklistId
     */
    public function setTasklistId($tasklistId)
    {
        $this->tasklistId = $tasklistId;
    }

    /**
     * @return null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param null $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }


}