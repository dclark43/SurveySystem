<?php
	session_start(); #start the session
	setcookie("user_id_cookie", $_SESSION['sessionID'], time() + (86400*30); //User Id currently expires after one month
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
        	$survey_ids=array();
        	$_SESSION['completed_surveys']=$survey_ids;
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
                                <form action="take_survey.php" method="get">
                                    <input type="submit" value="Take" name="action" class="btn btn-secondary" />             
                                    <input type="hidden" name="survey_id" value="<?php echo $survey['id'] ?>" />
                                </form> 
                            </td>                                              
                        </tr>
                    <?php endforeach; ?>
                    
              </tbody>

            </table>

        </div>

        <a href="new_survey.html" class="btn btn-secondary col-2">Create Survey</a>

    </body>
</html>
