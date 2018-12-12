<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
    <style>
        .box{
            padding:10px;
            border:gray 1px solid;
        }
    </style>
</head>
<body>
<?php include 'other' ?>
<div>
    <h2>comment (dont show any things)</h2>
    {{// this is a comment }}
</div>

<div class="box">
    <h2>For</h2>
    {{for a in ['a', 'b', 'c']}}
        {{a}}
    {{endfor}}
</div>
<br/>



</body>
</html>
