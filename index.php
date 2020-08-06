<?php
	if(!isset($_SESSION)) {
        session_start();
    }
	setcookie("user_id_cookie", session_id(), time() + (86400*30)); //User Id currently expires after one month
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 

        <meta name="author" content="David Clark and Evan West">
        <meta name="description" content="Survey System">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="styles/main.css">

        <title>Survey System</title>
    </head>

    <body>
        <?php
            if(!isset($_SESSION['completed_surveys'])) {
                $_SESSION['completed_surveys']=array();
            }
            if(!isset($_SESSION['created_surveys'])) {
                $_SESSION['created_surveys']=array();
            }
		?>
                
        <nav class="navbar navbar-expand-md bg-dark navbar-dark">
            <a class="navbar-brand" href="index.php">Survey System</a> 
        </nav>

        <h1 class="title col-10">Surveys</h1>

        <div class="container col-10">
            <table class="table table-borderless">
                <tbody>
                    <?php
                        require('connect-db.php');
                        global $db;

                        $query = "SELECT * FROM surveys ORDER BY id DESC";

                        $get_surveys = $db->prepare($query);
                        $get_surveys->execute();
                        $surveys = $get_surveys->fetchAll();

                        if($get_surveys->rowCount() == 0) {
                            echo "<tr><th class='name' scope='row'>No surveys found</th></tr>";
                        }
                    ?>

                    <?php foreach ($surveys as $survey): ?>
                        <tr>
                            <th class="name" scope="row">
                                <?php echo $survey['name']; ?> 
                            </th>
                            <td class="author">
                                <?php echo $survey['author']; ?> 
                            </td>        
                            <td class="action">
                                <!-- Display an 'X' button to delete a survey if it was created by the user -->
                                <?php if(in_array($survey['id'], $_SESSION['created_surveys'])): ?>
                                    <form action="delete_survey.php" method="post">
                                        <input type="submit" value="X" name="action" class="btn btn-secondary" onclick="return confirm('Are you sure you want to delete <?php echo $survey['name'] ?>?')" />
                                        <input type="hidden" name="survey_id" value="<?php echo $survey['id'] ?>" />
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td class="action">
                                <!-- Show 'Results' button if survey has been taken already or was created by the user -->
                                <?php if(in_array($survey['id'], $_SESSION['completed_surveys'])): ?>
                                    <form action="view_results.php" method="get">
                                        <input type="submit" value="Results" name="action" class="btn btn-secondary" />             
                                        <input type="hidden" name="survey_id" value="<?php echo $survey['id'] ?>" />
                                    </form>
                                <!-- Show 'Take' button if survey has not been taken -->
                                <?php else: ?>
                                    <form action="take_survey.php" method="get">
                                        <input type="submit" value="Take" name="action" class="btn btn-secondary" />             
                                        <input type="hidden" name="survey_id" value="<?php echo $survey['id'] ?>" />
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
              </tbody>

            </table>

        </div>

        <a href="new_survey.html" class="btn btn-secondary col-2">Create Survey</a>

    </body>
</html>
