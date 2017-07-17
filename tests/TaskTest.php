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

        function testGetDescription()
        {
            // Arrange
            $description = "Do dishes.";
            $completed = true;
            $test_task = new Task($description, $completed);

            // Act
            $result = $test_task->getDescription();

            // Assert
            $this->assertEquals($description, $result);
        }

        function testSetDescription()
        {
            // Arrange
            $description = "Do dishes.";
            $completed = true;
            $test_task = new Task($description, $completed);

            // Act
            $test_task->setDescription("Drink coffee.");
            $result = $test_task->getDescription();

            // Assert
            $this->assertEquals("Drink coffee.", $result);
        }

        function testGetCompleted()
        {
            // Arrange
            $description = "Do dishes.";
            $completed = false;
            $test_task = new Task($description, $completed);

            // Act
            $result = $test_task->getCompleted();

            // Assert
            $this->assertEquals($completed, $result);
        }

        function testSetCompleted()
        {
            // Arrange
            $description = "Do dishes.";
            $completed = true;
            $test_task = new Task($description, $completed);

            // Act
            $test_task->setCompleted(false);
            $result = $test_task->getCompleted();

            // Assert
            $this->assertEquals(false, $result);
        }

        function testGetId()
        {
            //Arrange
            $description = "Watch the new Thor movie.";
            $completed = true;
            $test_task = new Task($description, $completed);
            $test_task->save();

            //Act
            $result = $test_task->getId();

            //Assert
            $this->assertTrue(is_numeric($result));
        }

        function testSave()
        {
            //Arrange
            $description = "Eat breakfast";
            $completed = true;
            $test_task = new Task($description, $completed);

            //Act
            $executed = $test_task->save();

            //Assert
            $this->assertTrue($executed, "Task not successfully saved to database");
        }

        function testGetAll()
        {
            //Arrange
            $description = "Wash the dog";
            $completed = true;
            $test_task = new Task($description, $completed);
            var_dump($test_task);
            $test_task->save();
            var_dump($test_task);

            $description_2 = "Water the lawn";
            $completed_2 = 0;  // Ask Tyler
            $test_task_2 = new Task($description_2, $completed_2);
            var_dump($test_task_2);
            $test_task_2->save();
            var_dump($test_task_2);

            //Act
            $result = Task::getAll();

            //Assert
            $this->assertEquals([$test_task, $test_task_2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $description = "Wash the dog";
            $completed = true;
            $test_task = new Task($description, $completed);
            $test_task->save();

            $description2 = "Water the lawn";
            $completed2 = false;
            $test_task2 = new Task($description2, $completed2);
            $test_task2->save();

            //Act
            Task::deleteAll();

            //Assert
            $result = Task::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $description = "Wash the dog";
            $completed = true;
            $test_task = new Task($description, $completed);
            $test_task->save();

            $description_2 = "Water the lawn";
            $completed_2 = false;
            $test_task_2 = new Task($description_2, $completed_2);
            $test_task_2->save();

            //Act
            $result = Task::find($test_task->getId());

            //Assert
            $this->assertEquals($test_task, $result);
        }

        function testUpdateDescription()
        {
            // Arrange
            $description = "Wash the dog";
            $completed = true;
            $test_task = new Task($description, $completed);
            $test_task->save();

            $new_description = "Clean the dog";

            // Act
            $test_task->updateDescription($new_description);

            // Assert
            $this->assertEquals("Clean the dog", $test_task->getDescription());
        }

        function testUpdateCompleted()
        {
            // Arrange
            $description = "Wash the dog";
            $completed = true;
            $test_task = new Task($description, $completed);
            $test_task->save();

            $new_completed = false;

            // Act
            $test_task->updateCompleted($new_completed);

            // Assert
            $this->assertEquals(false, $test_task->getCompleted());
        }

        function testAddCategory()
        {
            // Arrange
            $name = "Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "File reports";
            $completed = true;
            $test_task = new Task($description, $completed);
            $test_task->save();

            // Act
            $test_task->addCategory($test_category);

            // Assert
            $this->assertEquals($test_task->getCategories(), [$test_category]);
        }

        function testGetCategories()
        {
            // Arrange
            $name = "Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            $name2 = "Volunteer stuff";
            $test_category2 = new Category($name2);
            $test_category2->save();

            $description = "File reports";
            $completed = true;
            $test_task = new Task($description, $completed);
            $test_task->save();

            // Act
            $test_task->addCategory($test_category);
            $test_task->addCategory($test_category2);

            // Assert
            $this->assertEquals($test_task->getCategories(), [$test_category, $test_category2]);
        }

        function testDelete()
        {
            // Arrange
            $name = "Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "File reports";
            $completed = true;
            $test_task = new Task($description, $completed);
            $test_task->save();

            // Act
            $test_task->addCategory($test_category);
            $test_task->delete();

            // Assert
            $this->assertEquals([], $test_category->getTasks());
        }

    }






 ?>
