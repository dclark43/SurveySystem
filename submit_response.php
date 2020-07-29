<?php
session_start();

require('connect-db.php');
global $db;

$valid_form = formValid($_POST);

if($valid_form[0] == "true") {
	foreach($_POST as $index => $value) {
		// Split the name of the input field at "-" into an array
		$split_name = explode("-", $index);
		if($split_name[0] == "question") {
			foreach($_POST['question-'.$split_name[1]] as $answer_index => $answer_value) {
				$question_id = $split_name[1];

				$question_query = "SELECT * FROM questions WHERE id=:question_id";
	            $get_question = $db->prepare($question_query);
	            $get_question->bindValue(':question_id', $question_id);
	            $get_question->execute();
	            $question_type = $get_question->fetchColumn(2);

	            if($question_type == "single" || $question_type == "multiple") {
	            	$answer_id = $answer_value;
	            	$number = NULL;
	            	$text = NULL;
	            }
	            else if($question_type == "number") {
	            	$number = $answer_value;
	            	$answer_id = NULL;
	            	$text = NULL;
	            }
	            else { // Text entry 
	            	$text = $answer_value;
	            	$answer_id = NULL;
	            	$number = NULL;
	            }
				addResponse($question_id, $answer_id, $number, $text);
			}
		}
	}
	header("Location: index.php");
}
else {
	// Show javascript alert and automatically reload new_survey form when alert is clicked
	echo "<script type='text/javascript'>if(!alert('$valid_form[1]')){window.history.back()};</script>";
}


// Return true if all questions are answered, return false with error message if not
function formValid($inputs) {
	global $db;
	
	foreach($inputs as $index => $value) {
		// Split the name of the input field at "-" into an array
		$split_name = explode("-", $index);
		if($split_name[0] == "question") {
			foreach($inputs['question-'.$split_name[1]] as $answer_index => $answer_value) {
				$question_id = $split_name[1];

				$question_query = "SELECT * FROM questions WHERE id=:question_id";
	            $get_question = $db->prepare($question_query);
	            $get_question->bindValue(':question_id', $question_id);
	            $get_question->execute();
	            $question_type = $get_question->fetchColumn(2);

	            if($question_type == "single" || $question_type == "number" || $question_type == "text") {
	            	if(empty($answer_value)) {
	            		return array("false", "Please enter a response for all questions");
	            	}
	            }
			}
		}
	}
	return array("true");
}


// Add a row to the responses table in the database
function addResponse($question_id, $answer_id, $number, $text) {
	global $db;

	$query = "INSERT INTO responses (question_id, answer_id, number_response, text_response) VALUES (:question_id, :answer_id, :number_response, :text_response)";

	$statement = $db->prepare($query);
	$statement->bindValue(':question_id', $question_id);
	$statement->bindValue(':answer_id', $answer_id);
	$statement->bindValue(':number_response', $number);
	$statement->bindValue(':text_response', $text);
	$statement->execute();
	
	$statement->closeCursor();
}

?>
