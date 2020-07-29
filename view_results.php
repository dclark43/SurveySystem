<?php
	start_session();
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

        <title>View Results</title>
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

        <h1 class="title col-10"><?php echo "View ".$survey_name." Results"; ?></h1>


        <div class="container col-10">
            <!-- Loop through questions and make a chart displaying resopnses for each of them -->
            <?php foreach ($questions as $question): ?>
                <h3><?php echo $question['question']; ?></h3>
                <div id="<?php echo 'chart'.$question['id']; ?>" style="height: 200px; width: 400px;"></div>
            <?php endforeach ?>
        </div>

        <!-- Library for graphing data from Google Charts, found at https://developers.google.com/chart/interactive/docs/gallery/barchart -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    </body>

    <?php
        global $db;

        $possible_answers = array();
        $answer_responses = array();

        foreach($questions as $question) {
            if($question['type'] == "single" || $question['type'] == "multiple") {
                $answers_query = "SELECT * FROM answers WHERE question_id=:question_id";
                $get_answers = $db->prepare($answers_query);
                $get_answers->bindValue(':question_id', $question['id']);
                $get_answers->execute();
                $answers = $get_answers->fetchAll();

                foreach($answers as $answer) {
                    array_push($possible_answers, [$answer['answer'] => $question['question']]);

                    $responses_query = "SELECT * FROM responses WHERE answer_id=:answer_id";
                    $get_responses = $db->prepare($responses_query);
                    $get_responses->bindValue(':answer_id', $answer['id']);
                    $get_responses->execute();
                    $responses = $get_responses->fetchAll();

                    array_push($answer_responses, [$answer['answer'] => $get_responses->rowCount()]);
                }
            }
            else {
                $responses_query = "SELECT * FROM responses WHERE question_id=:question_id";
                $get_responses = $db->prepare($responses_query);
                $get_responses->bindValue(':question_id', $question['id']);
                $get_responses->execute();
                $responses = $get_responses->fetchAll();

                foreach($responses as $response) {
                    if($question['type'] == "number") {
                        if(!in_array([$response['number_response'] => $question['question']], $possible_answers)) {
                            array_push($possible_answers, [$response['number_response'] => $question['question']]);
                        }

                        $number_responses_query = "SELECT * FROM responses WHERE number_response=:number_response, question_id=:question_id";
                        $get_number_responses = $db->prepare($number_responses_query);
                        $get_number_responses->bindValue(':number_response', $response['number_response']);
                        $get_number_responses->bindValue(':question_id', $question['id']);
                        $get_number_responses->execute();
                        $number_responses = $get_number_responses->fetchAll();

                        array_push($answer_responses, [$response['number_response'] => $get_number_responses->rowCount()]);

                    }
                    else {
                        if(!in_array([$response['text_response'] => $question['question']], $possible_answers)) {
                            array_push($possible_answers, [$response['text_response'] => $question['question']]);
                        }

                        $text_responses_query = "SELECT * FROM responses WHERE text_response=:text_response, question_id=:question_id";
                        $get_text_responses = $db->prepare($text_responses_query);
                        $get_text_responses->bindValue(':text_response', $response['text_response']);
                        $get_text_responses->bindValue(':question_id', $question['id']);
                        $get_text_responses->execute();
                        $text_responses = $get_text_responses->fetchAll();

                        array_push($answer_responses, [$response['text_response'] => $get_text_responses->rowCount()]);
                    }
                }
            }
        }

        $possible_answers_to_json=json_encode((array)$possible_answers);
        $answer_responses_to_json=json_encode((array)$answer_responses);
    ?>

    <script type="text/javascript">
        // Modified code from google charts website
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawCharts);
        
        function drawCharts() {
            var labels = ['Question', 'People', { role: 'style' }];
            var chart_array = [labels];

            var possible_answers=<?php echo $possible_answers_to_json ?>;
            var answer_responses=<?php echo $answer_responses_to_json ?>;

            var answers = [];
            var real_answers = [];

            for(i = 0; i < possible_answers.length; i++) {
                answers.push(Object.keys(possible_answers[i]));
            }

            for(i = 0; i < possible_answers.length; i++) {
                real_answers = answers[i];
            }

            // console.log(answers);
            // console.log(real_answers);
        }

        function drawQ1Chart() {
            var data = google.visualization.arrayToDataTable([
                ['Question', 'People', { role: 'style' }],
                ['I like dogs', 28, '#404040'],
                ['I am neutral', 7, '#404040'],
                ['I dislike dogs', 5, '#404040']
            ]);
            var options = {
                chartArea: {width: '50%'},
                legend: {position: 'none'},
                hAxis: {
                    minValue: 0
                }
            };
            var q1chart = new google.visualization.BarChart(document.getElementById('q1chart'));
            q1chart.draw(data, options);
        }

    </script>
</html>
