<?php
$file_path = "saved_text.txt";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["text"])) {
    file_put_contents($file_path, $_POST["text"]);
    echo json_encode([
        "text" => $_POST["text"],
        "message" => "Текст успешно сохранён",
    ]);
    exit();
}

if (
    $_SERVER["REQUEST_METHOD"] === "GET" &&
    isset($_GET["action"]) &&
    $_GET["action"] == "load"
) {
    $saved_text = file_exists($file_path) ? file_get_contents($file_path) : "";
    echo json_encode(["text" => $saved_text, "message" => "Текст загружен"]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple text field</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
      body, html {
          margin: 0;
          padding: 0;
          height: 100%;
          background: #f0f0f0;
          font-family: Arial, sans-serif;
      }

      #text {
          width: 98%;
          height: 85%;
          margin: 1%;
          padding: 10px;
          box-sizing: border-box;
          border: 1px solid #ccc;
          border-radius: 8px;
          background-color: #fff;
          color: #333;
          font-size: 16px;
          resize: none;
      }

      button {
          padding: 10px 20px;
          border: none;
          border-radius: 8px;
          background-color: #007BFF;
          color: white;
          font-size: 16px;
          cursor: pointer;
          transition: background-color 0.3s ease;
          outline: none;
          display: inline-block;
          margin: 0 auto;
      }

      button:hover {
          background-color: #0056b3;
      }


      #message {
          display: none;
          position: fixed;
          top: 10px;
          left: 50%;
          transform: translateX(-50%);
          background-color: #28a745;
          color: white;
          padding: 8px 15px;
          border-radius: 20px;
          box-shadow: 0 4px 6px rgba(0,0,0,0.1);
          text-align: center;
          z-index: 1000;
      }

    </style>
</head>
<body>
    <textarea id="text"></textarea><br>
    <center>
    <button id="save">Save</button>
    <button id="load">Load</button>
    </center>
    <div id="message"></div>

  <script>
  $(document).ready(function() {
      loadText();
      $('#text').focus();
      function showMessage(message) {
          $('#message').text(message).fadeIn(300).delay(1000).fadeOut(300);
      }

      function loadText() {
          $.getJSON('?action=load', function(response) {
              $('#text').val(response.text);
              showMessage(response.message);
          });
      }

      $('#save').click(function() {
          var text = $('#text').val();
          $.post(window.location.href, {text: text}, function(response) {
              $('#text').val(response.text);
              showMessage(response.message);
          }, 'json');
      });

      $('#load').click(function() {
          loadText();
      });

      $(document).on('keydown', function(event) {
          if ((event.ctrlKey || event.metaKey) && event.key === 's') {
              event.preventDefault();
              $('#save').click();
          }
      });
  });
  </script>
</body>
</html>
