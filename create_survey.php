<?php

require('connect-db.php');

$valid_form = formValid($_POST);

if($valid_form[0] == "true") {
	// Add name and author to survey table
	$survey_id = addSurvey($_POST['name'], $_POST['author']);
	$question_ids = array();

	// Loop through questions and add each to questions table
	foreach($_POST['question'] as $index => $value) {
	    $question_id = addQuestion($survey_id, $value);
	    array_push($question_ids, $question_id);
	}

	// Loop through hidden question type fields and update questions table
	foreach($_POST['type'] as $index => $value) {
		updateQuestionType($question_ids[$index], $value);
	}

	// Loop through answers and add each to answers table if it is a multiple or single choice question
	foreach($_POST as $index => $value) {
		// Split the name string at "-" into an array
		$split_name = explode("-", $index);
		if($split_name[0] == "answer") {
			foreach($_POST['answer-'.$split_name[1]] as $answer_index => $answer_value) {
				$question_id = $question_ids[$split_name[1]-1];
				addAnswer($question_id, $answer_value);
			}
		}
	}

	header("Location: index.php");
}
else {
	// Show javascript alert and automatically reload new_survey form when alert is clicked
	echo "<script type='text/javascript'>if(!alert('$valid_form[1]')){window.history.back()};</script>";
}


// Add a row to survey table and return survey id
function addSurvey($name, $author) {
	global $db;
	
	$query = "INSERT INTO surveys (name, author) VALUES (:name, :author)";
	
	$statement = $db->prepare($query);
	$statement->bindValue(':name', $name);
	$statement->bindValue(':author', $author);
	$statement->execute();
	
	$statement->closeCursor();
	return $db->lastInsertId();
}


// Add a row to questions table and return question id
function addQuestion($survey_id, $question) {
	global $db;

	$query = "INSERT INTO questions (survey_id, question) VALUES (:survey_id, :question)";

	$statement = $db->prepare($query);
	$statement->bindValue(':survey_id', $survey_id);
	$statement->bindValue(':question', $question);
	$statement->execute();
	
	$statement->closeCursor();
	return $db->lastInsertId();
}


// Update questions table to include question type
function updateQuestionType($question_id, $type) {
	global $db;

	$query = "UPDATE questions SET type=:type WHERE id=:question_id";

	$statement = $db->prepare($query);
	$statement->bindValue(':type', $type);
	$statement->bindValue(':question_id', $question_id);
	$statement->execute();
	
	$statement->closeCursor();
}


// Add a row to the answers table
function addAnswer($question_id, $answer) {
	global $db;

	$query = "INSERT INTO answers (question_id, answer) VALUES (:question_id, :answer)";

	$statement = $db->prepare($query);
	$statement->bindValue(':question_id', $question_id);
	$statement->bindValue(':answer', $answer);
	$statement->execute();
	
	$statement->closeCursor();
}


// Check if the form is valid
function formValid($inputs) {
	foreach($inputs as $index => $value) {
		if(empty($inputs['name'])) {
			return array("false", "Please enter a survey name");
		}
		else if(empty($inputs['author'])) {
			return array("false", "Please enter an author");
		}
		else {
			// Check if there is a value for each question
			foreach($inputs['question'] as $index => $value) {
			    if(empty($value)) {
			    	return array("false", "Please enter question ".($index + 1)." or remove it");
			    }
			}

			// Check if there is an answer type for each question
			foreach($inputs['type'] as $index => $value) {
				if(empty($value)) {
					return array("false", "Please select an answer type for question ".($index + 1));
				}
			}

			// Check if there is a value for each answer field
			foreach($inputs as $index => $value) {
				// Split the name string at "-" into an array
				$split_name = explode("-", $index);
				if($split_name[0] == "answer") {
					foreach($inputs['answer-'.$split_name[1]] as $answer_index => $answer_value) {
						if(empty($answer_value)) {
							return array("false", "Please enter something for answer ".($answer_index + 1)." of question ".($split_name[1]));
						}
					}
				}
			}

		}
	}
	return array("true");
}

?>