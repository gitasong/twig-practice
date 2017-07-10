<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Task.php";
    require_once "src/Category.php";


    $server = 'mysql:host=localhost:8889;dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    class TaskTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Task::deleteAll();
            Category::deleteAll();
        }

        function testGetId()
        {
            //Arrange
            $name = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "Wash the dog";
            $category_id = $test_category->getId();
            $test_task = new Task($description, $category_id);
            $test_task->save();

            //Act
            $result = $test_task->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function testSave()
        {
            //Arrange
            $name = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "Wash the dog";
            $category_id = $test_category->getId();
            $test_task = new Task($description, $category_id);

            //Act
            $executed = $test_task->save();

            //Assert
            $this->assertTrue($executed, "Task not successfully saved to database");
        }

        function testGetAll()
        {
            //Arrange
            $name = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();
            $category_id = $test_category->getId();

            $description = "Wash the dog";
            $test_task = new Task($description, $category_id);
            $test_task->save();

            $description_2 = "Water the lawn";
            $test_task_2 = new Task($description_2, $category_id);
            $test_task_2->save();

            //Act
            $result = Task::getAll();

            //Assert
            $this->assertEquals([$test_task, $test_task_2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $name = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();
            $category_id = $test_category->getId();

            $description = "Wash the dog";
            $test_task = new Task($description, $category_id);
            $test_task->save();

            $description2 = "Water the lawn";
            $test_task2 = new Task($description2, $category_id);
            $test_task2->save();

            //Act
            Task::deleteAll();

            //Assert
            $result = Task::getAll();
            $this->assertEquals([], $result);
        }

        function testGetCategoryId()
        {
            //Arrange
            $name = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();

            $category_id = $test_category->getId();
            $description = "Wash the dog";
            $test_task = new Task($description, $category_id);
            $test_task->save();

            //Act
            $result = $test_task->getCategoryId();

            //Assert
            $this->assertEquals($category_id, $result);;
        }

        function testFind()
        {
            //Arrange
            $name = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();
            $category_id = $test_category->getId();

            $description = "Wash the dog";
            $test_task = new Task($description, $category_id);
            $test_task->save();

            $description_2 = "Water the lawn";
            $test_task_2 = new Task($description_2, $category_id);
            $test_task_2->save();

            //Act
            $result = Task::find($test_task->getId());

            //Assert
            $this->assertEquals($test_task, $result);
        }
    }






 ?>
