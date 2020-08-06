<?php
if(!isset($_SESSION)) {
	session_start();
}

require('connect-db.php');

$survey_id = $_POST['survey_id'];

delete_survey($survey_id);
$question_ids = delete_questions($survey_id);
foreach($question_ids as $question_id) {
	delete_answers($question_id['id']);
	delete_responses($question_id['id']);
}

header("Location: index.php");



// Delete a survey from the surveys table given survey id
function delete_survey($id) {
	global $db;

	$query = "DELETE FROM surveys WHERE id=:id";
	$statement = $db->prepare($query);
	$statement->bindValue(':id', $id);
	$statement->execute();
	$statement->closeCursor();
}


// Delete all questions from the questions table given survey id
function delete_questions($survey_id) {
	global $db;

	$find_query = "SELECT id FROM questions WHERE survey_id=:survey_id";
	$find_questions = $db->prepare($find_query);
	$find_questions->bindValue(':survey_id', $survey_id);
	$find_questions->execute();
	$question_ids = $find_questions->fetchAll();
	$find_questions->closeCursor();

	$delete_query = "DELETE FROM questions WHERE survey_id=:survey_id";
	$delete_questions = $db->prepare($delete_query);
	$delete_questions->bindValue(':survey_id', $survey_id);
	$delete_questions->execute();
	$delete_questions->closeCursor();

	return $question_ids;
}


// Delete all answers from the answers table given question id
function delete_answers($question_id) {
	global $db;

	$query = "DELETE FROM answers WHERE question_id=:question_id";
	$statement = $db->prepare($query);
	$statement->bindValue(':question_id', $question_id);
	$statement->execute();
	$statement->closeCursor();
}


// Delete all responses from the responses table given question id
function delete_responses($question_id) {
	global $db;

	$query = "DELETE FROM responses WHERE question_id=:question_id";
	$statement = $db->prepare($query);
	$statement->bindValue(':question_id', $question_id);
	$statement->execute();
	$statement->closeCursor();
}


?>