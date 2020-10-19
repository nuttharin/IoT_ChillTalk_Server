<!doctype html>
<html>
<head>
   <meta charset="UTF-8">
   
   <title>Directory Contents</title>

   <link rel="stylesheet" href="./dist/css/.style.css">
   <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
   <link rel="stylesheet" href="./dist/css/bootstrap.min.css"  >
   <script src="./dist/js/.sorttable.js" type="text/javascript"></script>
   <script src="dist/js/jquery.js"></script>
   <script src='dist/js/bootstrap.min.js' ></script>

</head>
<style>
    table {
        border-collapse: collapse;
        border-radius: 1em;
        overflow: hidden;
    }

    td {
        padding: 3px;
        border-bottom: 1px solid white; 
    }

    th {
        padding: 0.7em;
    }

    #mySearch{
        float: right;
    }

    .headerTable{
        display: inline;
    }
    #barHeader{
        background-color: #DA5D1A;
        color: white;
        padding: 11px;
    }
</style>
<!-- <nav class="navbar navbar-expand-lg bg-dark navbar- justify-content-between">
    <ul class="navbar-nav">
        <li class="nav-item  text-white navbar-center">
            <h3>Report Daily Summary</h3>
        </li>
        <span class="navbar-text text-white navbar-right">
                  xxxxxxx
        </span>
        
    </ul>
     -->
<!-- </nav> -->
<div >
    <h2 id='barHeader'> Report Daily Summary</h2>

</div>
<body>
    <div id="container viewBody" style="padding: 50px !important;">
        
        <div class="headerTable">
         
            <button type="button" class="btn btn-primary" onclick="getAll()" id="btnAll" >ALL</button>
            <button type="button" class="btn btn-secondary" onclick="getIsp()" id="btnIsp">ISP</button>
            <button type="button" class="btn btn-success" id="btnDispatch" onclick="getDispatch()">Dispatch</button>
            <button type="button" class="btn btn-warning" id="btnCloseSpool" onclick="getCloseSpool()">Close Spool</button> 
        
        </div>
           
       
        <div class="headerTable">
            <input type="text" id="mySearch" onkeyup="tableSearch()" placeholder="Search for file names..">

        </div>
         <br>
        
        <table class="sortable" id="tableFile">
            <thead>
                <tr>
                    <th>Filename</th>
                    <th>Type</th>
                  
                </tr>
            </thead>
            <tbody>
                <?php   

                createTable();
                    function createTable()
                    {
                            // Checks to see if veiwing hidden files is enabled
                        if($_SERVER['QUERY_STRING']=="hidden")
                        {
                            $hide="";
                            $ahref="./";
                            $atext="Hide";
                        }
                        else
                        {   $hide=".";
                            $ahref="./?hidden";
                            $atext="Show";
                        }

                        // Opens directory
                        $myDirectory=opendir("./fileDownload");

                        // Gets each entry
                        while($entryName=readdir($myDirectory)) {
                        $dirArray[]=$entryName;
                        }

                        // Closes directory
                        closedir($myDirectory);

                        // Counts elements in array
                        $indexCount=count($dirArray);

                        // Sorts files
                        sort($dirArray);

                        // Loops through the array of files
                        for($index=0; $index < $indexCount; $index++) 
                        {

                        // Decides if hidden files should be displayed, based on query above.
                            if(substr("$dirArray[$index]", 0, 1)!=$hide) 
                            {

                                // Resets Variables
                                $favicon="";
                                $class="file";

                                // Gets File Names
                                $name=$dirArray[$index];
                                $namehref=$dirArray[$index];

                                // Gets Date Modified
                                //$modtime=date("M j Y g:i A", filemtime($dirArray[$index]));
                                //$timekey=date("YmdHis", filemtime($dirArray[$index]));

                                // Separates directories, and performs operations on those directories
                                if(is_dir($dirArray[$index]))
                                {
                                        $extn="&lt;Directory&gt;";
                                        $size="&lt;Directory&gt;";
                                        $sizekey="0";
                                        $class="dir";

                                    // Gets favicon.ico, and displays it, only if it exists.
                                        if(file_exists("$namehref/favicon.ico"))
                                            {
                                                $favicon=" style='background-image:url($namehref/favicon.ico);'";
                                                $extn="&lt;Website&gt;";
                                            }

                                    // Cleans up . and .. directories
                                        if($name=="."){$name=". (Current Directory)"; $extn="&lt;System Dir&gt;"; $favicon=" style='background-image:url($namehref/.favicon.ico);'";}
                                        if($name==".."){$name=".. (Parent Directory)"; $extn="&lt;System Dir&gt;";}
                                }

                                // File-only operations
                                else{
                                    // Gets file extension
                                    $extn=pathinfo($dirArray[$index], PATHINFO_EXTENSION);

                                    // Prettifies file type
                                    switch ($extn){
                                        case "png": $extn="PNG Image"; break;
                                        case "jpg": $extn="JPEG Image"; break;
                                        case "jpeg": $extn="JPEG Image"; break;
                                        case "svg": $extn="SVG Image"; break;
                                        case "gif": $extn="GIF Image"; break;
                                        case "ico": $extn="Windows Icon"; break;

                                        case "txt": $extn="Text File"; break;
                                        case "log": $extn="Log File"; break;
                                        case "htm": $extn="HTML File"; break;
                                        case "html": $extn="HTML File"; break;
                                        case "xhtml": $extn="HTML File"; break;
                                        case "shtml": $extn="HTML File"; break;
                                        case "php": $extn="PHP Script"; break;
                                        case "js": $extn="Javascript File"; break;
                                        case "css": $extn="Stylesheet"; break;

                                        case "pdf": $extn="PDF Document"; break;
                                        case "xls": $extn="Spreadsheet"; break;
                                        case "xlsx": $extn="Spreadsheet"; break;
                                        case "doc": $extn="Microsoft Word Document"; break;
                                        case "docx": $extn="Microsoft Word Document"; break;

                                        case "zip": $extn="ZIP Archive"; break;
                                        case "htaccess": $extn="Apache Config File"; break;
                                        case "exe": $extn="Windows Executable"; break;

                                        default: if($extn!=""){$extn=strtoupper($extn)." File";} else{$extn="Unknown";} break;
                                    }

                                    // Gets and cleans up file size
                                        //$sizekey=filesize($dirArray[$index]);
                                }
                                echo("
                                <tr class='$class'>
                                    <td><a href='./fileDownload/$namehref'$favicon class='listTable' value='$name' typeFile='$extn' link='./fileDownload/$namehref'$favicon >$name</a></td>
                                    <td>$extn</td>
                                    
                                </tr>");
                            }
                        }
                    }
                    
                ?>

            </tbody>
        </table>

        
    </div>
</body>
</html>

<script>


    let listTableAll = "";
    let listTableIsp = "";
    let listTableDispatch = "";
    let listTableCloseSpool = "";

    function tableSearch() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("mySearch");
        filter = input.value.toUpperCase();
        table = document.getElementById("tableFile");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
            }       
        }
    }

   

    function getAll(){
        //console.log("getAll")

        $("#tableFile tbody").empty();           
        $("#tableFile tbody").html(listTableAll);
    }

    function getIsp(){
        
        //console.log("getIsp")

        $("#tableFile tbody").empty();
        $("#tableFile tbody").html(listTableIsp);
        //console.log(listTable);
    }

    function getDispatch(){
       
        //console.log("getDispatch")

        $("#tableFile tbody").empty();           
        $("#tableFile tbody").html(listTableDispatch);
    }

    function getCloseSpool(){
        //console.log("btnCloseSpool")
        $("#tableFile tbody").empty();
        $("#tableFile tbody").html(listTableCloseSpool);
    }

    

    $(document).ready(function(){
        
        
        
        $(".listTable").each(function() {
            var link = $(this).attr("link");
            let typeFile = $(this).attr("typeFile");
            let value = $(this).attr('value');

            listTableAll +=  "<tr class=''> <td>"+
                                "<a href="+link+" class='name' typeFile="+typeFile+" link="+link+" >"+value+"</a>"+
                                "<td>"+typeFile+"</td>"+                            
                            "</td></tr>";

            if(value.split("_")[2] == "ISP")
            {

                listTableIsp +=  "<tr class=''> <td>"+
                                "<a href="+link+" class='name' typeFile="+typeFile+" link="+link+" >"+value+"</a>"+
                                "<td>"+typeFile+"</td>"+                            
                            "</td></tr>";
            }
            else if(value.split("_")[2] == "Dispatch"){
                listTableDispatch +=  "<tr class=''> <td>"+
                                "<a href="+link+" class='name' typeFile="+typeFile+" link="+link+" >"+value+"</a>"+
                                "<td>"+typeFile+"</td>"+                            
                            "</td></tr>";
            }
            else if(value.split("_")[2] == "CloseSpool"){
                listTableCloseSpool +=  "<tr class=''> <td>"+
                                "<a href="+link+" class='name' typeFile="+typeFile+" link="+link+" >"+value+"</a>"+
                                "<td>"+typeFile+"</td>"+                            
                            "</td></tr>";
            }
            
           
            //console.log(link);
            // compare id to what you want
        });         

        // console.log(listTableAll)
        // console.log(listTableIsp)
        // console.log(listTableDispatch)
        // console.log(listTableCloseSpool)

        
    })



    
</script>
