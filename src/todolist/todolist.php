<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "php_study";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM todolist WHERE todo_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$todos = [];
while ($row = $result->fetch_assoc()) {
    $todos[] = $row;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <style>
        .popup {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #000;
            padding: 20px;
            background: #fff;
            z-index: 1000;
        }
        .popup-overlay {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .deleteButton{
            margin-left: 10px;
        }
    </style>
</head>
<body>
<form action="../homepage.php">
    <button type="submit">홈페이지</button>
</form>
<h1>할일 목록</h1>
<button id="openPopup">Open Popup</button>

<div id="popupOverlay" class="popup-overlay"></div>
<div id="popup" class="popup">
    <div id="popupContent">
        <h2>할일 추가</h2>
        <form id="todoForm" method="post" action="add_todolist.php">
            <label for="todo">할일:</label>
            <input type="text" id="todo" name="todo" required><br><br>
            <label for="date">날짜:</label>
            <input type="date" id="date" name="date" required><br><br>
            <input type="submit" value="추가">
        </form>
    </div>
    <button id="closePopup">팝업 닫기</button>
</div>
<ul id="todoList">
    <?php foreach ($todos as $todo): ?>
        <li id="todo_<?php echo $todo['todo_num']; ?>">
            <?php echo htmlspecialchars($todo['todo'])  . '&nbsp&nbsp&nbsp' . htmlspecialchars($todo['date']); ?>
            <span class="deleteButton" data-todo-num="<?php echo $todo['todo_num']; ?>">삭제</span>
        </li>
    <?php endforeach; ?>
</ul>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fetchButton = document.getElementById("openPopup");
        const todoList = document.getElementById("todoList");

        fetchButton.addEventListener("click", function() {
            document.getElementById("popupOverlay").style.display = "block";
            document.getElementById("popup").style.display = "block";
        });

        document.getElementById("closePopup").onclick = function() {
            document.getElementById("popupOverlay").style.display = "none";
            document.getElementById("popup").style.display = "none";
        };

        document.getElementById("todoForm").onsubmit = function(event) {
            event.preventDefault();
            var form = document.getElementById("todoForm");
            var formData = new FormData(form);

            fetch('add_todolist.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        alert('새로운 할일이 성공적으로 추가되었습니다.');
                        document.getElementById("popupOverlay").style.display = "none";
                        document.getElementById("popup").style.display = "none";
                        fetchToDoList();
                    } else {
                        alert('할일 추가 중 오류가 발생했습니다: ' + data);
                    }
                })
                .catch(error => console.error('Error:', error));
        };
        todoList.addEventListener("click", function(event) {
            if (event.target.classList.contains("deleteButton")) {
                const todoNum = event.target.getAttribute("data-todo-num");
                if (confirm("정말로 이 할일을 삭제하시겠습니까?")) {
                    deleteTodoItem(todoNum);
                }
            }
        });

        function deleteTodoItem(todoNum) {
            fetch('delete_todolist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ todo_num: todoNum }),
            })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        alert('할일이 성공적으로 삭제되었습니다.');
                        fetchToDoList(); // 할 일 목록을 다시 불러오기
                    } else {
                        alert('할일 삭제 중 오류가 발생했습니다: ' + data);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function fetchToDoList() {
            fetch('fetch_todolist.php')
                .then(response => response.json())
                .then(data => {
                    todoList.innerHTML = '';

                    data.forEach(todo => {
                        const li = document.createElement("li");
                        li.setAttribute("id", `todo_${todo.todo_num}`);
                        li.innerHTML = `${todo.todo}  ${formatDate(todo.date)}`;

                        const deleteSpan = document.createElement("span");
                        deleteSpan.classList.add("deleteButton");
                        deleteSpan.setAttribute("data-todo-num", todo.todo_num);
                        deleteSpan.textContent = "삭제";

                        li.appendChild(deleteSpan);
                        todoList.appendChild(li);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            let month = date.getMonth() + 1;
            let day = date.getDate();

            if (month < 10) {
                month = '0' + month;
            }
            if (day < 10) {
                day = '0' + day;
            }

            return `${year}-${month}-${day}`;
        }
    });
</script>
</body>
</html>

