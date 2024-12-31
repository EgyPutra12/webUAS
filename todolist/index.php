<!DOCTYPE html>
<html>
<head>
    <title>To-Do List</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dark-container">
        <div class="container">
            <h1>To-Do List</h1>
            <div class="input-container">
                <input type="text" id="taskInput" placeholder="Tambahkan tugas..." autofocus>
                <input type="datetime-local" id="deadlineInput">
                <button onclick="addTask()">Tambah</button>
            </div>
            <ul id="taskList">
            </ul>
            <div id="taskCount">
                Total Tugas: 0 | Selesai: 0
            </div>
        </div>
        <footer>
            <a href="about.php" class="footer-link">About Me</a>
        </footer>
    </div>

    <script>
        function addTask() {
            const taskInput = document.getElementById("taskInput");
            const deadlineInput = document.getElementById("deadlineInput");
            const taskList = document.getElementById("taskList");
            const taskText = taskInput.value.trim();
            const deadline = deadlineInput.value;

            if (taskText !== "") {
                const li = document.createElement("li");
                li.innerHTML = `
                    <label class="checkbox-container">
                        <input type="checkbox" onchange="updateTaskStatus(this)">
                        <span contenteditable="true" onblur="saveTasks()" onkeydown="handleEnter(event)">${taskText}</span>
                    </label>
                    <span class="deadline">${deadline ? formatDate(deadline) : ""}</span>
                    <button onclick="removeTask(this)">Hapus</button>
                `;
                taskList.appendChild(li);
                taskInput.value = "";
                deadlineInput.value = "";
                taskInput.focus();
                saveTasks();
                updateTaskCount();
            }
        }

        function removeTask(button) {
            const li = button.parentNode;
            li.remove();
            saveTasks();
            updateTaskCount();
        }

        function saveTasks() {
            const tasks = [];
            const taskListItems = document.querySelectorAll("#taskList li");
            taskListItems.forEach(item => {
                tasks.push({
                    text: item.querySelector("span").textContent,
                    checked: item.querySelector("input[type='checkbox']").checked,
                    deadline: item.querySelector(".deadline").textContent
                });
            });
            localStorage.setItem("tasks", JSON.stringify(tasks));
        }

        function loadTasks() {
            const storedTasks = localStorage.getItem("tasks");
            if (storedTasks) {
                const tasks = JSON.parse(storedTasks);
                const taskList = document.getElementById("taskList");
                taskList.innerHTML = "";
                tasks.forEach(task => {
                    const li = document.createElement("li");
                    li.innerHTML = `
                        <label class="checkbox-container">
                            <input type="checkbox" ${task.checked ? "checked" : ""} onchange="updateTaskStatus(this)">
                            <span contenteditable="true" onblur="saveTasks()" onkeydown="handleEnter(event)">${task.text}</span>
                        </label>
                        <span class="deadline">${task.deadline ? task.deadline : ""}</span>
                        <button onclick="removeTask(this)">Hapus</button>
                    `;
                    taskList.appendChild(li);
                });
            }
            updateTaskCount();
        }

        function updateTaskCount() {
            const taskListItems = document.querySelectorAll("#taskList li");
            const totalTasks = taskListItems.length;
            let completedTasks = 0;

            taskListItems.forEach(item => {
                if (item.querySelector("input[type='checkbox']").checked) {
                    completedTasks++;
                }
            });

            const taskCountElement = document.getElementById("taskCount");
            taskCountElement.textContent = `Total Tugas: ${totalTasks} | Selesai: ${completedTasks}`;
        }

        function handleEnter(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                event.target.blur();
            }
        }

        function updateTaskStatus(checkbox){
            saveTasks();
            updateTaskCount();
        }

        function formatDate(datetimeString) {
            if (!datetimeString) return ""; // Handle jika deadline kosong
            const date = new Date(datetimeString);
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            try {
                return date.toLocaleDateString('id-ID', options);
            } catch (error) {
                console.error("Error formatting date:", error);
                return "Invalid Date"; // Handle error parsing tanggal
            }
        }

        window.onload = loadTasks;
        document.getElementById("taskInput").focus();
    </script>
</body>
</html>