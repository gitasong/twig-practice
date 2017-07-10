<?php
class Task
{

    private $description;
    private $category_id;
    private $id;

    function __construct($description, $category_id, $id = null)
    {
        $this->description = $description;
        $this->category_id = $category_id;
        $this->id = $id;
    }

    function setDescription($new_description)
    {
        $this->description = (string) $new_description;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getId()
    {
        return $this->id;
    }

    function getCategoryId()
    {
        return $this->category_id;
    }

    function save()
    {
        $executed = $GLOBALS['DB']->exec("INSERT INTO tasks (description, category_id) VALUES ('{$this->getDescription()}', {$this->getCategoryId()})");
        if ($executed) {
            $this->id = $GLOBALS['DB']->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    static function getAll()
    {
        $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
        $tasks = array();
        foreach($returned_tasks as $task) {
            $task_description = $task['description'];
            $task_id = $task['id'];
            $category_id = $task['category_id'];
            $new_task = new Task($task_description, $category_id, $task_id);
            array_push($tasks, $new_task);
        }
        return $tasks;
    }

    static function deleteAll()
    {
       $GLOBALS['DB']->exec("DELETE FROM tasks;");
    }

    static function find($search_id)
    {
        $returned_tasks = $GLOBALS['DB']->prepare("SELECT * FROM tasks WHERE id = :id");
        $returned_tasks->bindParam(':id', $search_id, PDO::PARAM_STR);
        $returned_tasks->execute();
        foreach ($returned_tasks as $task) {
            $task_description = $task['description'];
            $category_id = $task['category_id'];
            $task_id = $task['id'];
            if ($task_id == $search_id) {
               $found_task = new Task($task_description, $category_id, $task_id);
            }
        }

        return $found_task;
    }
}
?>
