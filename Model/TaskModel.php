<?php

/**
 * Class UserModel
 */
class TaskModel implements Serializable
{
    private $taskId = 0;
    private $userId = 0;
    private $tasklistId = 0;
    private $content = null;
    private $status = null;
    private $created = null;
    private $updated = null;

    public function serialize()
    {
        return array(
            'task_id' => $this->taskId,
            'user_id' => $this->userId,
            'tasklist_id' => $this->tasklistId,
            'content' => $this->content,
            'status' => $this->status,
            'created' => $this->created,
            'updated' => $this->updated
            );
    }

    public function unserialize($pdoResults)
    {
        $this->setTaskId($pdoResults['task_id']);
        $this->setUserId($pdoResults['user_id']);
        $this->setTaskListId($pdoResults['tasklist_id']);
        $this->setContent($pdoResults['content']);
        $this->setStatus($pdoResults['status']);
        $this->setDateCreation($pdoResults['created']);
        $this->setDateUpdate($pdoResults['updated']);
    }

    public function setTaskModel($userId, $taskListId, $content, $status)
    {
        $this->setUserId($userId);
        $this->setTaskListId($taskListId);
        $this->setContent($content);
        $this->setStatus($status);
    }

    /**
     * @return int
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * @param int $taskId
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;
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
    public function getTaskListId()
    {
        return $this->tasklistId;
    }

    /**
     * @param int $tasklistId
     */
    public function setTaskListId($tasklistId)
    {
        $this->tasklistId = $tasklistId;
    }

    /**
     * @return null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param null $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param null $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return null
     */
    public function getDateCreation()
    {
        return $this->created;
    }

    /**
     * @param null $date_creation
     */
    public function setDateCreation($date_creation)
    {
        $this->created = $date_creation;
    }

    /**
     * @return null
     */
    public function getDateUpdate()
    {
        return $this->date_update;
    }

    /**
     * @param null $date_update
     */
    public function setDateUpdate($date_update)
    {
        $this->updated = $date_update;
    }
}
