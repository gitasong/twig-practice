<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Category.php";
    require_once "src/Task.php";

    $server = 'mysql:host=localhost:8889;dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CategoryTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
          Category::deleteAll();
          Task::deleteAll();
        }

        function testGetName()
        {
            //Arrange
            $name = "Work stuff";
            $test_category = new Category($name);

            //Act
            $result = $test_category->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

       function testSave()
        {
            //Arrange
            $name = "Work stuff";
            $test_category = new Category($name);

            //Act
            $executed = $test_category->save();

            // Assert
            $this->assertTrue($executed, "Category not successfully saved to database");
        }

        function testGetId()
        {
            //Arrange
            $name = "Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            //Act
            $result = $test_category->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function testGetAll()
        {
            //Arrange
            $name = "Work stuff";
            $name_2 = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();
            $test_category_2 = new Category($name_2);
            $test_category_2->save();

            //Act
            $result = Category::getAll();

            //Assert
            $this->assertEquals([$test_category, $test_category_2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $name = "Wash the dog";
            $name_2 = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();
            $test_category_2 = new Category($name_2);
            $test_category_2->save();

            //Act
            Category::deleteAll();
            $result = Category::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $name = "Wash the dog";
            $name2 = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();
            $test_category_2 = new Category($name2);
            $test_category_2->save();

            //Act
            $result = Category::find($test_category->getId());

            //Assert
            $this->assertEquals($test_category, $result);
        }

        function testGetTasks()
        {
            //Arrange
            $name = "Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            $test_category_id = $test_category->getId();

            $description = "Email client";
            $test_task = new Task($description, $test_category_id);
            $test_task->save();

            $description_2 = "Meet with boss";
            $test_task_2 = new Task($description_2, $test_category_id);
            $test_task_2->save();

            //Act
            $result = $test_category->getTasks();

            //Assert
            $this->assertEquals([$test_task, $test_task_2], $result);
        }
    }

?>
