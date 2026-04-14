<!DOCTYPE html>
<html>
<head>
    <title>Certificate</title>  
    <style>
        @page {
            margin: 0px;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            text-align: center;
            padding-top: 250px; /* increased padding-top to avoid overlapping top border/graphics of certificate */
            margin: 0px;
            background-image: url('{{ public_path("img/certificate.png") }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100%;
            width: 100%;
        }
        h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            margin: 10px 0;
        }

        .student-name
        {
            font-size:70px;
            color:#d09705;
            position: absolute;
            width:100%;
            top: 42%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .description1
        {
            position: absolute;
            top: 54%;
            left: 50%;
            transform: translate(-50%, -50%);
            width:80%;
            padding:10px;
        }   

        .description2
        {
            position: absolute;
            top: 63%;
            left: 50%;
            transform: translate(-50%, -50%);
            width:80%;
            padding:10px;
        }

        .courses-list
        {
            position: absolute;
            top: 73%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60%;
            text-align: left;
        }
        .courses-list table {
            width: 100%;
            font-size: 16px;
        }
        .courses-list td {
            width: 50%;
            padding: 5px;
            vertical-align: top;
        }

        .footer-layout {
            position: absolute;
            top: 88%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
        }

    </style>
</head>

<body>

<p class="student-name">{{ $student->full_name }}</p>

<p class="description1">
of <i>{{ $student->institute->name ?? '...' }}, {{ $student->institute->city ?? '...' }} </i> for
successfully completing the Technology and Innovation Program
conducted by Acadevo Research Labs during the academic year
{{ $student->created_at ? $student->created_at->format('Y') . '–' . ((int)$student->created_at->format('Y') + 1) : '2026–2027' }}.
</p>

<p class="description2">
During the course of this program, the participant has demonstrated
strong technical aptitude and acquired practical, hands-on
experience in the following domains:
</p>

<div class="courses-list">
    <table>
        @foreach($student->studentCourses->chunk(2) as $chunk)
        <tr>
            @foreach($chunk as $studentCourse)
            <td>&bull; {{ $studentCourse->course->name ?? '' }}</td>
            @endforeach
            @if($chunk->count() == 1)
            <td></td>
            @endif
        </tr>
        @endforeach
    </table>
</div>




<div class="footer-layout">
    <table style="width: 100%; font-size: 16px;">
        <tr>
            <td style="width: 50%; vertical-align: bottom; text-align: left;">
                <b>Certificate ID:</b> ACD-{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}
            </td>
            <td style="width: 50%; vertical-align: bottom; text-align: right;">
                <div style="float: right; text-align: center; width: 220px;">
                    <div style="border-bottom: 1px solid #000; height: 30px; margin-bottom: 5px;"></div>
                    <b>Executive Director</b>
                </div>
            </td>
        </tr>
    </table>
</div>

</body>

</html>
