<?php
class Task
{

    private $description;
    private $completed;
    private $id;

    function __construct($description, $completed = false, $id = null)
    {
        $this->description = $description;
        $this->completed = $completed;
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

    function setCompleted($new_completed)
    {
        $this->completed = (bool) $new_completed;
    }

    function getCompleted()
    {
        return $this->completed;
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $executed = $GLOBALS['DB']->exec("INSERT INTO tasks (description, completed) VALUES ('{$this->getDescription()}', '{$this->getCompleted()}')");
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
            $description = $task['description'];
            $completed = $task['completed'];
            $id = $task['id'];
            $new_task = new Task($description, $completed, $id);
            array_push($tasks, $new_task);
        }
        return $tasks;
    }

    static function deleteAll()
    {
        $executed = $GLOBALS['DB']->exec("DELETE FROM tasks;");
            if ($executed) {
                return true;
            } else {

                return false;
            }
    }

    static function find($search_id)
    {
        $found_task = null;
        $returned_tasks = $GLOBALS['DB']->prepare("SELECT * FROM tasks WHERE id = :id");
        $returned_tasks->bindParam(':id', $search_id, PDO::PARAM_STR);
        $returned_tasks->execute();
        foreach ($returned_tasks as $task) {
            $description = $task['description'];
            $completed = $task['completed'];
            $id = $task['id'];
            if ($id == $search_id) {
               $found_task = new Task($description, $completed, $id);
            }
        }

        return $found_task;
    }

    function updateDescription($new_description)
    {
        $executed = $GLOBALS['DB']->exec("UPDATE tasks SET description = '{$new_description}' WHERE id = {$this->getId()};");
        if ($executed) {
           $this->setDescription($new_description);
           return true;
        } else {
           return false;
        }
    }

    function updateCompleted($new_completed)
    {
        $executed = $GLOBALS['DB']->exec("UPDATE tasks SET completed = '{$new_completed}' WHERE id = {$this->getId()};");
        if ($executed) {
           $this->setCompleted($new_completed);
           return true;
        } else {
           return false;
        }
    }

    function delete()
    {
        $executed = $GLOBALS['DB']->exec("DELETE FROM tasks WHERE id = {$this->getId()};");
         if (!$executed) {
            return false;
        }
        $executed = $GLOBALS['DB']->exec("DELETE FROM categories_tasks WHERE task_id = {$this->getId()};");
         if (!$executed) {
            return false;
        } else {
            return true;
        }
    }

    function addCategory($category)
    {
        $executed = $GLOBALS['DB']->exec("INSERT INTO categories_tasks (category_id, task_id) VALUES ({$category->getId()}, {$this->getId()});");
            if ($executed) {
                return true;
            } else {
                return false;
            }
    }

    function getCategories()
    {
        $query = $GLOBALS['DB']->query("SELECT category_id FROM categories_tasks WHERE task_id = {$this->getId()};");
        $category_ids = $query->fetchAll(PDO::FETCH_ASSOC);

        $categories = array();
        foreach($category_ids as $id) {
            $category_id = $id['category_id'];
            $result = $GLOBALS['DB']->query("SELECT * FROM categories WHERE id = {$category_id};");
            $returned_category = $result->fetchAll(PDO::FETCH_ASSOC);

            $name = $returned_category[0]['name'];
            $id = $returned_category[0]['id'];
            $new_category = new Category($name, $id);
            array_push($categories, $new_category);
        }
        return $categories;
    }
}
?>
