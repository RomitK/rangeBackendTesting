<!DOCTYPE html>
<html>

<head>
    <title>Lead Stat Report</title>
</head>

<body>
    <p>Hi {{$data['userName']}}, </p>
    <p>The last report that was shared with you today contains some mistakes(offplan, ready, rent count), Please find the stat which are live on the website</p>

    <table style="border-collapse: collapse;">

        <thead>
            <tr style="border-bottom: 1px solid #000;">
                <th colspan="2" style="border: 1px solid #000; padding: 8px;">Website Stat</th>
            </tr>
            <tr style="border-bottom: 1px solid #000;">
                <th style="border: 1px solid #000; padding: 8px;">Type</th>
                <th style="border: 1px solid #000; padding: 8px;">Count</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th style="border: 1px solid #000; padding: 8px;">Communities</th>
                <th style="border: 1px solid #000; padding: 8px;">{{ $data['communities'] }}</th>
            </tr>

            <tr>
                <th style="border: 1px solid #000; padding: 8px;">Projects</th>
                <th style="border: 1px solid #000; padding: 8px;">{{ $data['projects'] }}</th>
            </tr>

            <tr>
                <th style="border: 1px solid #000; padding: 8px;">Properties</th>
                <th style="border: 1px solid #000; padding: 8px;">{{ $data['properties'] }}</th>
            </tr>

            <tr>
                <th style="border: 1px solid #000; padding: 8px;">Careers</th>
                <th style="border: 1px solid #000; padding: 8px;">{{ $data['careers'] }}</th>
            </tr>

            <tr>
                <th style="border: 1px solid #000; padding: 8px;">Guides</th>
                <th style="border: 1px solid #000; padding: 8px;">{{ $data['guides'] }}</th>
            </tr>

            <tr>
                <th style="border: 1px solid #000; padding: 8px;">Media</th>
                <th style="border: 1px solid #000; padding: 8px;">{{ $data['allMedias'] }}</th>
            </tr>
            <tr>
                <th style="border: 1px solid #000; padding: 8px;">Team</th>
                <th style="border: 1px solid #000; padding: 8px;">{{ $data['teams'] }}</th>
            </tr>
        </tbody>


        <thead>
            <tr style="border-bottom: 1px solid #000;">
                <th colspan="2" style="border: 1px solid #000; padding: 8px;">Properties Stat</th>
            </tr>
            <tr style="border-bottom: 1px solid #000;">
                <th style="border: 1px solid #000; padding: 8px;">Type</th>
                <th style="border: 1px solid #000; padding: 8px;">Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['propertiesTypes'] as $type=>$count)
            <tr style="border-bottom: 1px solid #000;">
                <td style="border: 1px solid #000; padding: 8px;">{{$type}}</td>
                <td style="border: 1px solid #000; padding: 8px;">{{$count}}</td>
            </tr>
            @endforeach
            <tr>
                <th style="border: 1px solid #000; padding: 8px;">Total</th>
                <th style="border: 1px solid #000; padding: 8px;">{{ $data['properties'] }}</th>
            </tr>
        </tbody>


        <thead>
            <tr style="border-bottom: 1px solid #000;">
                <th colspan="2" style="border: 1px solid #000; padding: 8px;">Media Stat</th>
            </tr>
            <tr style="border-bottom: 1px solid #000;">
                <th style="border: 1px solid #000; padding: 8px;">Type</th>
                <th style="border: 1px solid #000; padding: 8px;">Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['types'] as $type=>$count)
            <tr style="border-bottom: 1px solid #000;">
                <td style="border: 1px solid #000; padding: 8px;">{{$type}}</td>
                <td style="border: 1px solid #000; padding: 8px;">{{$count}}</td>
            </tr>
            @endforeach
            <tr>
                <th style="border: 1px solid #000; padding: 8px;">Total</th>
                <th style="border: 1px solid #000; padding: 8px;">{{ $data['allMedias'] }}</th>
            </tr>
        </tbody>

    </table>

    <p>Please go to the <a href="https://range.ae/">website</a> to get the more details.</p>
</body>

</html>