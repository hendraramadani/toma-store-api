
<!DOCTYPE html>
<html>
    
<head>
    <title>{{$title}}</title>
    <style>
    .page-break {
        page-break-after: always;
    }
    table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
  padding: 5px;
}
    .table{
        width: 100%;
    }

    header {
                position: fixed;
                top: -45px;
                left: 0px;
                right: 0px;
                height: 50px;

                /** Extra personal styles **/
                background-color: #FFBB00FF;
                color: black;
                text-align: center;
                line-height: 35px;
            }


            footer {
                position: fixed; 
                bottom: -45px; 
                left: 0px; 
                right: 0px;
                height: 50px; 

                /** Extra personal styles **/
                background-color: #FFBB00FF;
                color: black;
                text-align: center;
                line-height: 35px;
            }
    </style>
    
    

</head>
<body>
    <header>
        Generated : {{$timestamp}}
    </header>
    <footer>
        Dokumen Resmi TOMA STORE
    </footer>
  <H1>{{$title}}</H1>
<div>
    @foreach($users as $data)
        @include('account.table', ['data' => $data])
    @endforeach
</div>
   
</body>
</html>