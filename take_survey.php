<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
	
		<meta name="author" content="Evan West">
		<meta name="description" content="Survey System">
	
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="styles/main.css">
		
		<style> 
			.questionList{
			font-family: Candara;
			}
			
			yesRadio:checked {
				background-color: green;
			}
			
			noRadio:checked {
				background-color: red;
			}
			h2 {
				font-size: 18px;
			}
		</style>
		
		<title>Take Survey</title>
	</head>

	<body>
		<nav class="navbar navbar-expand-md bg-dark navbar-dark">
    		<a class="navbar-brand" href="index.php">Survey System</a> 
    	</nav>

    	<?php
            require('connect-db.php');
            global $db;

            $survey_query = "SELECT * FROM surveys WHERE id=:survey_id";
            $get_survey = $db->prepare($survey_query);
            $get_survey->bindValue(':survey_id', $_GET['survey_id']);
            $get_survey->execute();
            $survey_name = $get_survey->fetchColumn(1);

            $questions_query = "SELECT * FROM questions WHERE survey_id=:survey_id";
            $get_questions = $db->prepare($questions_query);
            $get_questions->bindValue(':survey_id', $_GET['survey_id']);
            $get_questions->execute();
            $questions = $get_questions->fetchAll();
        ?>

        <h1 class="title col-10"><?php echo $survey_name; ?></h1>

    	<form name="submit_response" action="submit_response.php" method="post">
    	
    		<div class="container col-10"> <!-- Meant to contain the survey in its entirety -->
    		    		
    			<div class="question"> <!-- Meant to contain the question list and input fields -->
    				<?php foreach ($questions as $question): ?>
                        <h2><?php echo $question['question']; ?></h2>
                        <br/>
                        <?php
                        	$answers_query = "SELECT * FROM answers WHERE question_id=:question_id";
                        	$get_answers = $db->prepare($answers_query);
				            $get_answers->bindValue(':question_id', $question['id']);
				            $get_answers->execute();
				            $answers = $get_answers->fetchAll();
                        ?>

                        <?php if($question['type'] == "single"): ?>
                        	<?php foreach ($answers as $answer): ?>
                        		<input type="radio" name="<?php echo 'question-'.$question['id'].'[]'; ?>" value="<?php echo $answer['id']; ?>"> <?php echo $answer['answer']; ?>
                        		<br/>
                        		<br/>
                       		<?php endforeach; ?>
                        <?php endif; ?>

                        <?php if($question['type'] == "multiple"): ?>
                        	<?php foreach ($answers as $answer): ?>
                        		<input type="checkbox" name="<?php echo 'question-'.$question['id'].'[]'; ?>" value="<?php echo $answer['id']; ?>"> <?php echo $answer['answer']; ?>
                        		<br/>
                        		<br/>
                       		<?php endforeach; ?>
                        <?php endif; ?>

                        <?php if($question['type'] == "number"): ?>
                        	<input type="number" class="form-control col-2" name="<?php echo 'question-'.$question['id'].'[]'; ?>">
                        	<br/>
                        	<br/>
                        <?php endif; ?>

                        <?php if($question['type'] == "text"): ?>
                        	<input type="text" class="form-control" name="<?php echo 'question-'.$question['id'].'[]'; ?>">
                        	<br/>
                        <?php endif; ?>

                        <br/>
                        <br/>
                    <?php endforeach; ?>
    			</div>
    		</div>
    		<input type="submit" class="btn btn-dark submit col-2" id="submit" value="Submit"/>
    		<a href="index.php" class="btn btn-light cancel col-2">Cancel</a>
    	</form>
    	
    	

	</body>
	
	<script>
		var dogName = document.getElementById('dogName');
		var questionsForm = document.getElementById('questionsForm');
		var elementOfError = document.getElementById('error'); //Which error messages did the user activate?
		
		form.addEventListener('submit', (e) => { //Should check if any errors exist before allowing a submit to happen
			var errorMessages = [];
			if (dogName.value == null || dogName.value === '') { //Meant to ensure the textfield isn't empty when trying to submit
				errorMessages.push('Name field cannot be empty');
			}
			
			if (dogName.value.length > 15) {
				errorMessages.push('Name cannot exceed 15 characters') //Meant to ensure text in the textfield isn't overly long 
			}
			
			if (errorMessages.length > 0) { 
				e.preventDefault(); //Prevent submitting if any input errors have occured
				elementOfError.innerText = errorMessages.join(', ');
			}
		}
	</script>
</html>