// Set focus to survey name input field on page load
function setFocus() {
    var nameInput = document.getElementById("name");
    nameInput.focus();
}

function singleChoice() {
    var menu = event.target.parentNode;
    var dropdown = menu.parentNode;
    var answerDiv = dropdown.parentNode;

    // Set button text
    var button = menu.previousElementSibling;
    button.textContent = "Single Choice";

    // Don't add another "Add Answer" button if there is already one there
    var inputElements = answerDiv.getElementsByTagName("input");
    for(var i = 0; i < inputElements.length; i++) {
        if(inputElements[i].value == "+ Add Answer") {
            return;
        }
    }

    // Create and add "Add Answer" Button
    var lineBreak = document.createElement("br");
    answerDiv.appendChild(lineBreak);

    var addAnswerButton = document.createElement("input");
    addAnswerButton.type = "button"
    addAnswerButton.className = "btn btn-dark"
    addAnswerButton.value = "+ Add Answer"
    addAnswerButton.addEventListener("click", addAnswer);
    answerDiv.insertBefore(addAnswerButton, lineBreak);

    // Don't add another answer field when selecting answer type
    var labelElements = answerDiv.getElementsByTagName("label");
    if(labelElements.length > 1) {
        return;
    }

    var questionCount = 0;
    // form is a Jquery object, cant use getElementsByTagName();
    var questionElements = $('form').find('div');
    for(var i = 0; i < questionElements.length; i++) {
        if(questionElements[i].className == "question") {
            questionCount++;
        }
    }

    // Create and add an answer text input field
    var answerField = document.createElement("input");
    answerField.type = "text";
    answerField.className = "form-control";
    answerField.id = "answer-" + (labelElements.length + 1);
    answerField.name = "answer-" + (questionCount) + "[]";
    var answerFieldDiv = document.createElement("div");
    answerFieldDiv.className = "form-group";
    var answerFieldLabel = document.createElement("label");
    answerFieldLabel.for = answerField.id;
    answerFieldLabel.textContent = "Answer " + (labelElements.length + 1);

    answerDiv.insertBefore(answerFieldDiv, addAnswerButton);
    answerFieldDiv.appendChild(answerFieldLabel);
    answerFieldDiv.appendChild(answerField);

    // Set hidden type value for use in form
    var hidden_type = menu.getElementsByTagName("input")[0];
    hidden_type.value = "single";
}

function multipleChoice() {
    var menu = event.target.parentNode;
    var dropdown = menu.parentNode;
    var answerDiv = dropdown.parentNode;
    
    // Set button text
    var button = menu.previousElementSibling;
    button.textContent = "Multiple Choice";

    // Don't add another "Add Answer" button if there is already one there
    var inputElements = answerDiv.getElementsByTagName("input");
    for(var i = 0; i < inputElements.length; i++) {
        if(inputElements[i].value == "+ Add Answer") {
            return;
        }
    }

    // Create and add "Add Answer" Button
    var lineBreak = document.createElement("br");
    answerDiv.appendChild(lineBreak);

    var addAnswerButton = document.createElement("input");
    addAnswerButton.type = "button"
    addAnswerButton.className = "btn btn-dark"
    addAnswerButton.value = "+ Add Answer"
    addAnswerButton.addEventListener("click", addAnswer);
    answerDiv.insertBefore(addAnswerButton, lineBreak);

    // Don't add another answer field when selecting answer type
    var labelElements = answerDiv.getElementsByTagName("label");
    if(labelElements.length > 1) {
        return;
    }

    var questionCount = 0;
    // form is a Jquery object, cant use getElementsByTagName();
    var questionElements = $('form').find('div');
    for(var i = 0; i < questionElements.length; i++) {
        if(questionElements[i].className == "question") {
            questionCount++;
        }
    }

    // Create and add an answer text input field
    var answerField = document.createElement("input");
    answerField.type = "text";
    answerField.className = "form-control";
    answerField.id = "answer-" + (labelElements.length + 1);
    answerField.name = "answer-" + (questionCount) + "[]";
    var answerFieldDiv = document.createElement("div");
    answerFieldDiv.className = "form-group";
    var answerFieldLabel = document.createElement("label");
    answerFieldLabel.for = answerField.id;
    answerFieldLabel.textContent = "Answer " + (labelElements.length + 1);

    answerDiv.insertBefore(answerFieldDiv, addAnswerButton);
    answerFieldDiv.appendChild(answerFieldLabel);
    answerFieldDiv.appendChild(answerField);

    // Set hidden type value for use in form
    var hidden_type = menu.getElementsByTagName("input")[0];
    hidden_type.value = "multiple";
}

function numberSelect() {
    var menu = event.target.parentNode;
    var dropdown = menu.parentNode;
    var answerDiv = dropdown.parentNode;
    
    // Set button text
    var button = menu.previousElementSibling;
    button.textContent = "Number Select";

    // Clear any answers or buttons that might be there
    var lineBreaks = answerDiv.getElementsByTagName("br");
    for(var i = 0; i < lineBreaks.length; i++) {
        lineBreaks[i].remove();
    }

    // Weird bug here where loop only removes every other element, temporary fix run loop 10 times
    var labelElements = answerDiv.getElementsByTagName("label");
    for(var j = 0; j < 10; j++) {
        for(var i = 0; i < labelElements.length; i++) {
            labelElements[i].parentNode.remove();
        }    
    }

    var inputElements = answerDiv.getElementsByTagName("input");
    for(var i = 0; i < inputElements.length; i++) {
        if(inputElements[i].value == "+ Add Answer") {
            inputElements[i].remove();
        }
    }

    // Set hidden type value for use in form
    var hidden_type = menu.getElementsByTagName("input")[0];
    hidden_type.value = "number";
}

function textEntry() {
    var menu = event.target.parentNode;
    var dropdown = menu.parentNode;
    var answerDiv = dropdown.parentNode;
    
    // Set button text
    var button = menu.previousElementSibling;
    button.textContent = "Text Entry";

    // Clear any answers or buttons that might be there
    var lineBreaks = answerDiv.getElementsByTagName("br");
    for(var i = 0; i < lineBreaks.length; i++) {
        lineBreaks[i].remove();
    }

    // Weird bug here where loop only removes every other element, temporary fix run loop 10 times
    var labelElements = answerDiv.getElementsByTagName("label");
    for(var j = 0; j < 10; j++) {
        for(var i = 0; i < labelElements.length; i++) {
            labelElements[i].parentNode.remove();
        }    
    }

    var inputElements = answerDiv.getElementsByTagName("input");
    for(var i = 0; i < inputElements.length; i++) {
        if(inputElements[i].value == "+ Add Answer") {
            inputElements[i].remove();
        }
    }

    // Set hidden type value for use in form
    var hidden_type = menu.getElementsByTagName("input")[0];
    hidden_type.value = "text";
}

// Create and add an additional answer text input field
function addAnswer() {
    var answerDiv = event.target.parentNode;
    var questionLabelDiv = answerDiv.previousElementSibling;
    var labelElements = answerDiv.getElementsByTagName("label");
    var answerField = document.createElement("input");
    answerField.type = "text";
    answerField.className = "form-control removable";
    answerField.id = "answer-" + (labelElements.length + 1);
    var questionNumber = questionLabelDiv.getElementsByTagName("label")[0].textContent.split(" ")[1];
    answerField.name = "answer-" + questionNumber + "[]";

    var answerRemoveButton = document.createElement("button");
    answerRemoveButton.className = "btn btn-dark remove-button";
    answerRemoveButton.textContent = "X";
    answerRemoveButton.addEventListener("click", removeAnswer);

    var answerFieldDiv = document.createElement("div");
    answerFieldDiv.className = "form-group";
    var answerFieldLabel = document.createElement("label");
    answerFieldLabel.for = answerField.id;
    answerFieldLabel.textContent = "Answer " + (labelElements.length + 1);

    answerDiv.insertBefore(answerFieldDiv, event.target);
    answerFieldDiv.appendChild(answerFieldLabel);
    answerFieldDiv.appendChild(answerField);
    answerFieldDiv.appendChild(answerRemoveButton);
}

function addQuestion() {
    var form = event.target.parentNode;
    var questionDiv = document.createElement("div");
    questionDiv.className = "question";
    form.insertBefore(questionDiv, event.target);

    var questionCount = 0;
    // form is a Jquery object, cant use getElementsByTagName();
    var questionElements = $('form').find('div');
    for(var i = 0; i < questionElements.length; i++) {
        if(questionElements[i].className == "question") {
            questionCount++;
        }
    }

    var questionNameDiv = document.createElement("div");
    questionNameDiv.className = "form-group";
    var questionNameInput = document.createElement("input");
    questionNameInput.type = "text";
    questionNameInput.name = "question[]"
    questionNameInput.className = "form-control removable";
    var questionRemoveButton = document.createElement("button");
    questionRemoveButton.className = "btn btn-dark remove-button";
    questionRemoveButton.textContent = "X";
    questionRemoveButton.addEventListener("click", removeQuestion);
    var questionNameLabel = document.createElement("label");
    questionNameLabel.textContent = "Question " + questionCount;
    questionNameDiv.appendChild(questionNameLabel);
    questionNameDiv.appendChild(questionNameInput);
    questionNameDiv.appendChild(questionRemoveButton);
    questionDiv.appendChild(questionNameDiv);


    var answerDiv = document.createElement("div");
    answerDiv.className = "answer";
    questionDiv.appendChild(answerDiv);
    dropdownDiv = document.createElement("div");
    dropdownDiv.className = "form-group dropdown";
    answerDiv.appendChild(dropdownDiv);
    // Dropdown menu
    var dropdownButton = document.createElement("button");
    dropdownButton.className = "btn btn-light dropdown-toggle";
    dropdownButton.type = "button";
    dropdownButton.setAttribute("data-toggle", "dropdown");
    dropdownButton.textContent = "Answer Type";
    var menuDiv = document.createElement("div");
    menuDiv.className = "dropdown-menu";
    dropdownDiv.appendChild(dropdownButton);
    dropdownDiv.appendChild(menuDiv);

    var singleOption = document.createElement("a");
    singleOption.className = "dropdown-item";
    singleOption.href = "#";
    singleOption.addEventListener("click", singleChoice);
    singleOption.textContent = "Single Choice";

    var multipleOption = document.createElement("a");
    multipleOption.className = "dropdown-item";
    multipleOption.href = "#";
    multipleOption.addEventListener("click", multipleChoice);
    multipleOption.textContent = "Multiple Choice";

    var numberOption = document.createElement("a");
    numberOption.className = "dropdown-item";
    numberOption.href = "#";
    numberOption.addEventListener("click", numberSelect);
    numberOption.textContent = "Number Select";

    var textOption = document.createElement("a");
    textOption.className = "dropdown-item";
    textOption.href = "#";
    textOption.addEventListener("click", textEntry);
    textOption.textContent = "Text Entry";

    var hidden_type = document.createElement("input");
    hidden_type.type = "hidden";
    hidden_type.name = "type[]";

    menuDiv.appendChild(singleOption);
    menuDiv.appendChild(multipleOption);
    menuDiv.appendChild(numberOption);
    menuDiv.appendChild(textOption);
    menuDiv.appendChild(hidden_type);
}

function removeAnswer() {
    var answerParent = event.target.parentNode.parentNode;
    var answerDiv = event.target.parentNode;
    answerDiv.remove();

    // Update answer numbers
    var labelElements = answerParent.getElementsByTagName("label");
    for(var i = 0; i < labelElements.length; i++) {
        labelElements[i].textContent = "Answer " + (i + 1);
    }
}

function removeQuestion() {
    var questionNameDiv = event.target.parentNode;
    var questionDiv = questionNameDiv.parentNode;
    questionDiv.remove();

    // Update question numbers
    var labelElements = $('form').find('label');
    var questionNum = 1;
    for(var i = 0; i < labelElements.length; i++) {
        if(labelElements[i].parentNode.parentNode.className == "question") {
            labelElements[i].textContent = "Question " + questionNum;
            questionNum++;
        }
    }
}

function validateForm() {
    var nameField = document.findElementById("name");
    if(nameField.length == 0) {
        alert("Please enter a Survey name");
        return false;
    }

    var questionField = document.findElementById("question1");
    if(questionField.length == 0) {
        alert("Please enter a question");
        return false;
    }

    window.location.href = index.html;
}
