<!DOCTYPE html>
<html>
    
<head>
    <title>{{$title}}</title>
    <style>
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
.garis1{
    top : -50px;
  border-top:3px solid black;
  height: 2px;
  border-bottom:1px solid black;
}

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


    .crop {
        height: 100px;
        width: 200px;
        overflow: hidden;
    }

    .crop img {
        width: 200px;
        height: 100px;
        margin: 0px 0 0 0px;
    }
    .noBorder {
    border:none !important;
}
/* body {font-family: arial; background-color : #ccc } */
    .kopkasurat {width : 650px;margin:0 auto;background-color : #fff;height: 100px;padding: 20px;}
    table {border-bottom : 5px solid # 000; padding: 2px,}
    .tengah {text-align : center;line-height: 5px; border:none !important;}

    #camat{
  text-align:right;
  padding-right: 50px;
}
#nama-camat{
  margin-top:100px;
  text-align:right;
  padding-right: 25px;
}

#nip-camat{
  text-align:right;
}

    </style>
</head>

<body>
<div>
    <header>
        <div class = "kopsurat" >
            <table width = "100%" class="noBorder">
                  <tr class="noBorder">
                    <td class="noBorder"></td>
                    
                        <td class="noBorder"> <img src="{{public_path('img.jpg')}}" width="150px" > </td>
                        <td class = "tengah">
                              <h2>PT. TOMA CAHAYA NUSANTARA</h2>
                              <b>Jl. Onggatmit, Rimba Jaya, Kec.Merauke, Kab.Merauke, Papua</b><br><br><br><br>
                              <b>Kode Pos: 99615, +62822-3878-2452</b>
                        </td>
                        <td class="noBorder"></td>
                        <td class="noBorder"></td>
                        <td class="noBorder"></td>
                        <td class="noBorder"></td>
                        <td class="noBorder"></td>
                        <td class="noBorder"></td>
                        <td class="noBorder"></td>
                   </tr>
            </table >
       </div>
    </header>
    <hr class="garis1"/>

    <div style="text-align : center;">
        <H3>{{$title}}</H3>
    </div>

    <div>
        @foreach($content as $data)
            @include('order.table', ['data' => $data])
        @endforeach
    </div>
    
    <div class="page-break"> </div>
    <div style="display: flex; justify-content: flex-end" class="row">
        <div>
            <p id="camat"><strong>Manager</strong></p>
            <div id="nama-camat"><strong><u>Rizky Pattiasina</u></strong><br />
            </div>
            <div id="nip-camat"><u>NIP. 196703221995031001</u></strong><br />
            </div>
            
        </div>
      
    </div>
    <footer>
        Dokumen Resmi TOMA STORE | Generated : {{$timestamp}}
    </footer>
</body>
</html>
{{-- 
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
    @foreach($content as $data)
        @include('order.table', ['data' => $data])
    @endforeach
</div>
   
</body>
</html> --}}