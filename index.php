<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background-image: url('images/image1.jpg');
            background-size: cover;  
            background-repeat: no-repeat;
            background-position: center; 
            height: 100%; 
            margin: 0; 
            padding: 0; 
        }

        .top-bar {
            display: flex;
            justify-content: center; 
            gap: 50px; 
            padding: 10px 0;
            color: #333; 
            font-family: Arial, sans-serif; 
        }

        .top-bar a {
            background-color: #f9f9f9; 
            color: #333;
            text-decoration: none; 
            font-size: 16px; 
            display: flex; 
            align-items: center; 
            padding: 8px 16px; 
            border-radius: 5px; 
            transition: background-color 0.3s, color 0.3s;
        }

        .top-bar a:hover {
            background-color: #007BFF;
            color: white; 
        }

        .top-bar i {
            margin-right: 8px; 
        }




        
        .image-container {
            margin-top: 100px;
            text-align: center;
        }

        .image-container img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
        }

        h1 {
            font-family: Arial, sans-serif;
            font-size: 40px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            color: black;
            text-shadow: 2px 2px 4px white;
        }
        
        .box-container {
            display: flex;
            justify-content: center; 
            flex-wrap: wrap; 
            gap: 20px;
            margin-top: 150px; 
            padding: 0 20px;
        }

        .box {
            flex: 1 0 200px;
            text-align: center; 
        }

        .box img {
            background-color: rgba(255, 255, 255, 0.5); 
            width: 120px; 
            height: 128px; 
            object-fit: cover; 
            border-radius: 10px;
            transition: transform 0.3s ease-out, box-shadow 0.3s ease-out;
        }

        .box img:hover {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .box {
                flex-basis: calc(50% - 20px); 
            }
        }

        @media (max-width: 576px) {
           
            .box {
                flex-basis: 100%; 
            }
        }
    </style>
</head>
<body>
  
    <div class="image-container">
        <img src="images/csab.png" alt="Image">
    </div>
    
    <h1>Colegio San Agustin - Bacolod</h1>
    
<div class="top-bar">
    <a href="admin_login.php"><i class="fas fa-user-shield"></i> Admin</a>
    <a href="stud_login.php"><i class="fas fa-user-graduate"></i> Student</a>
    <a href="faculty_login.php"><i class="fas fa-user-tie"></i> Faculty</a>
</div>



    <div class="box-container">
        <div class="box"><img src="images/bed.png" alt="Box 1"></div>
        <div class="box"><img src="images/cabecs.png" alt="Box 2"></div>
        <div class="box"><img src="images/chap.png" alt="Box 3"></div>
        <div class="box"><img src="images/coe.png" alt="Box 4"></div>
        <div class="box"><img src="images/case.png" alt="Box 5"></div>
    </div>

</body>
</html>
