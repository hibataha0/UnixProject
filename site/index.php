<?php

$host = 'db'; 
$username = 'root'; 
$password = '123'; 
$database = 'mydb'; 

try {
    
    $connect = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


$word = '';
$results = [];
$error = '';






if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['word'])) {
    $word = trim($_POST['word']);

    if (!empty($word)) {
        try {
            
            $stmt = $connect->prepare("SELECT synonym FROM dbw WHERE word = :word");
            $stmt->execute(['word' => $word]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error = "Error fetching results: " . $e->getMessage();
        }
    } else {
        $error = "Please enter a valid word.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Synonym Search</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --text-primary: #1f2937;
            --text-secondary: #4b5563;
            --background: #f9fafb;
            --card-background: #ffffff;
            --border: #e5e7eb;
            --focus-ring: rgba(79, 70, 229, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .container {
            background-color: var(--card-background);
            width: 100%;
            max-width: 600px;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                        0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.025em;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        label {
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        input[type="text"] {
            padding: 0.75rem 1rem;
            border: 2px solid var(--border);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s ease;
            width: 100%;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--focus-ring);
        }

        input[type="text"]::placeholder {
            color: #9ca3af;
        }

        button {
            background-color: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        button:active {
            transform: translateY(0);
        }

        .result {
            margin-top: 2rem;
            padding: 1.5rem;
            background-color: var(--background);
            border-radius: 0.5rem;
            border: 1px solid var(--border);
        }

        .result p {
            text-align: center;
            color: var(--text-secondary);
            font-size: 1.125rem;
        }

        .result strong {
            color: var(--primary);
            font-weight: 600;
        }

        .search-icon {
            width: 1.25rem;
            height: 1.25rem;
            fill: currentColor;
        }

        .result-animation {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 640px) {
            .container {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Word Synonym Search</h1>
        
        <form method="POST">
            <div class="input-group">
                <label for="word">Enter  a word</label>
                <input 
                    type="text" 
                    id="word" 
                    name="word" 
                    required 
                    placeholder="Type any word to find its synonym..."
                    autocomplete="off"
                >
            </div>
            <button type="submit">
                <svg class="search-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                </svg>
                Search Synonym
            </button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['word'])): ?>
            <div class="result result-animation">
                <?php
                if (!empty($results)) {
                    foreach ($results as $record) {
                        echo "<p>The synonym for <strong>$word</strong> is: <strong>{$record['synonym']}</strong></p>";
                    }
                } else {
                    echo "<p>No synonym found for <strong>$word</strong>.</p>";
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
