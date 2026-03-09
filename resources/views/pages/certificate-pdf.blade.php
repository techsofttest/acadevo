<!DOCTYPE html>
<html>
<head>
    <title>Certificate</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            text-align: center;
            padding-top: 100px;
        }
        h1 {
            font-size: 28px;
        }
        p {
            font-size: 18px;
        }
    </style>
</head>
<body>

    <h1>Certificate of Completion</h1>

    <p>This is to certify that</p>

    <h2>{{ $student->full_name }}</h2>

    <p>
        Division / Class: {{ $student->division }} <br>
        College: {{ $student->institute->name }}
    </p>

    <p>Has attended the courses at Acadevo</p>



</body>
</html>
