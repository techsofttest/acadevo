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

    <h2>{{ $certificate->name }}</h2>

    <p>
        Date of Birth: {{ $certificate->date_of_birth }} <br>
        College: {{ $certificate->college }}
    </p>

    <p>
        Certificate Code: {{ $certificate->code }}
    </p>

</body>
</html>
