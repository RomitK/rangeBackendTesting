<!DOCTYPE html>
<html>

<head>
    <title>Data Export</title>
</head>

<body>
    <p>Hello HR, </p>
    <p>I hope this message finds you well.I wanted to inform you that we have received a new CV from our website.</p>
    <p>Applicant’s Name: {{$data['name']}}</p>
    <p>Applicant’s Email: {{$data['email']}}</p>
    <p>Applicant’s phone : {{$data['contact_number']}}</p>
    <p>Position Applied For: {{$data['position']}}</p>
    
    <p>Please <a href="{{$data['cv']}}" download="">Click</a> to download the CV</p>

</body>

</html>
