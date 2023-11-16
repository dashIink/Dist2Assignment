<?php
    session_start();
    
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness App Dashboard</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Modern_pentathlon_pictogram.svg/2048px-Modern_pentathlon_pictogram.svg.png');
            background-repeat: no-repeat;
            background-size: 100% 150%;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
        }

        h2 {
            color: #4caf50;
        }

        .dashboard {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .card {
            flex: 1;
            background-color: #e0e0e0;
            padding: 20px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .card h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .graph-container {
            margin-top: 20px;
        }

        .workout-builder {
            margin-top: 20px;
        }

        .logout {
            text-align: center;
            margin-top: 20px;
        }

        .logout a {
            text-decoration: none;
            color: #4caf50;
            font-weight: bold;
        }

        .form-container {
            margin-top: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
        }

        input {
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            width: 100%;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        select {
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            width: 100%;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Fitness App Dashboard</h2>

        <div class="dashboard" onclick="showSteps()">
            <div class="card">
                <h3>Today's Steps</h3>
                <!-- Add specific content or data here -->
            </div>

        </div>

        <div class="dashboard" onclick="showBuilder()">
        <div class="card">
                <h3>Workout Builder</h3>
                
            </div>
        </div>
    <div style = "display: none" id = "stepsContainer">
        <!-- Graph Section -->
        <div class="graph-container">
            <h3>Steps Taken</h3>
            <canvas id="stepsChart" width="400" height="200"></canvas>
        </div>

        <!-- Form to input steps -->
        <div class="form-container">
            <h3>Input Steps</h3>
            <form id="stepsForm">
                <label for="stepInput">Enter Steps:</label>
                <input type="number" id="stepInput" name="stepInput" required>

                <button type="button" onclick="addSteps()">Add Steps</button>
            </form>
        </div>
    </div>
    <div class="workout-builder" id = "workoutBuilder" style="display:none">
                <form id="workoutForm">
                        <label for="workoutSelect">Select Workout:</label>
                        <select id="workoutSelect" name="workoutSelect">
                            <option value="push-ups">Push-ups</option>
                            <option value="sit-ups">Sit-ups</option>
                            <option value="jogging">Jogging</option>
                            <option value="pull-ups">Pull-ups</option>
                            <option value="benchpress">Bench Press</option>
                            <option value="squats">Squats</option>
                            <!-- Add more workout options as needed -->
                        </select>

                        <label for="workoutDetails">Enter Specifics Here:</label>
                        <input type="text" id="workoutDetails" name="workoutDetails" placeholder="Enter Specifics Here">

                        <button type="button" onclick="addWorkout()">Add Workout</button>
                        <button type="button" onclick="deleteWorkout()">Delete Workout</button>
                    </form>

                    <h4>Workout List:</h4>
                    <ul id="workoutList"></ul>
                </div>

        <div class="logout">
            <a href="login.php">Logout</a>
        </div>
    </div>

    <script>
        // Sample data for the steps chart
        let stepsData = {
            labels: [],
            datasets: [{
                label: 'Steps Taken',
                data: [],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // Get the chart canvas and render the steps chart
        const stepsChartCanvas = document.getElementById('stepsChart').getContext('2d');
        const stepsChart = new Chart(stepsChartCanvas, {
            type: 'bar',
            data: stepsData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


        function showSteps(){
            if (document.getElementById("stepsContainer").style.display == "block"){
                document.getElementById("stepsContainer").style.display = "none";
            }
            else{
                document.getElementById("stepsContainer").style.display = "block";
                updateStepsChart();
            }
            
           
        }
        function showBuilder(){
            if (document.getElementById("workoutBuilder").style.display == "block"){
                document.getElementById("workoutBuilder").style.display = "none";
            }
            else{
                document.getElementById("workoutBuilder").style.display = "block";

            }
            
           
        }

        // Function to update the chart with new steps
        function updateStepsChart() {


            const userAction = async () => {
                
                const response = await fetch('http://localhost/DistAssign2/index.php/steps/<?php echo $_SESSION["id"];?>');
                const myJson = await response.json(); //extract JSON from the http response
                console.log(myJson);
                console.log(myJson[2][0]);
                const stepInput = document.getElementById('stepInput');
                
                stepsData.datasets[0].data = [];
                // Add new steps to the dataset
                if (myJson[2][0] != null){
                    stepsData.labels = myJson[2][1];
                    for(var i = 0; i < myJson[2][0].length; i++){
                        stepsData.datasets[0].data.push(myJson[2][0][i])
                    }
                }
                

                // Update the chart
                stepsChart.update();

                // Clear the input field
                stepInput.value = '';
                
            }
            userAction();
           
        }
        function addSteps(){
            const userAction = async () => {
                var stepsTaken = document.getElementById("stepInput").value;
                console.log(stepsTaken);
                const response = await fetch('http://localhost/DistAssign2/index.php/stepsadd?steps='+stepsTaken+'&id='+<?php echo $_SESSION["id"];?>+'', {method: 'PUT'});
                const myJson = await response.json(); //extract JSON from the http response
                console.log(myJson);
                // do something with myJson
                updateStepsChart();

            }
            userAction();
            
        }
        function addWorkout() {
            const userAction = async () => {
                var workoutType = document.getElementById("workoutSelect").value;
                var workoutSpecifics = document.getElementById("workoutDetails").value;
                const response = await fetch('http://localhost/DistAssign2/index.php/workoutAdd?type='+workoutType+'&id=<?php echo $_SESSION["id"];?>&specifics='+workoutSpecifics+'', {method: 'POST'});
                const myJson = await response.json(); //extract JSON from the http response
                console.log(myJson);
                // do something with myJson
                updateWorkout();

            }
            userAction();
        }

        function deleteWorkout() {
            const userAction = async () => {
                var stepsTaken = document.getElementById("stepInput").value;
                console.log(stepsTaken);
                const response = await fetch('http://localhost/DistAssign2/index.php/workoutDelete?id=<?php echo $_SESSION["id"];?>', {method: 'DELETE'});
                const myJson = await response.json(); //extract JSON from the http response
                console.log(myJson);
                // do something with myJson
                updateWorkout();
            }
            userAction();
        }

        function updateWorkout(){

            const userAction = async () => {
                
                const response = await fetch('http://localhost/DistAssign2/index.php/workout/<?php echo $_SESSION["id"];?>');
                const myJson = await response.json(); //extract JSON from the http response
                console.log(myJson);
                console.log(myJson[2][0]);
                const workoutSelect = document.getElementById('workoutSelect');
                const workoutDetails = document.getElementById('workoutDetails');
                const selectedWorkout = workoutSelect.value;
                const specifics = workoutDetails.value;

                // Create a new list item with workout details and add it to the workout list
                const workoutList = document.getElementById('workoutList');

                
                while (workoutList.firstChild) {
                    workoutList.removeChild(workoutList.firstChild);
                }

                const listItem = document.createElement('li');
                if (myJson[2][0] != null){
                    for(var i = 0; i < myJson[2][0].length; i++){
                        const listItem = document.createElement('li');
                        listItem.textContent = `${myJson[2][0][i]} - ${myJson[2][1][i]}`;
                        console.log(listItem.textContent);
                        workoutList.appendChild(listItem);
                    }
                }

                // Clear the input fields
                workoutDetails.value = '';
                
            }
            userAction();
            
        }
        updateWorkout()
    </script>
</body>
</html>