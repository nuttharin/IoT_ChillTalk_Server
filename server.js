var net = require('net');
var allFunc = require('./functions');
var port = 1000 ;

var server = net.createServer();

server.on("connection", function(socket){
   
    var remoteAddress = socket.remoteAddress + ":" + socket.remotePort ;
    console.log("new client connection is made %s",remoteAddress);

    socket.on("data", function(data){
        // format data 
        // รูปแบบ.  Output

        // ID /Date / time / data 
        // A11111111/16-9-2020/09.30/23.00

        // ส่งวันละ 4 ครั้ง (set ได้)
        // Data ที่อ่านได้ส่งมาเลย 0.5-4.5 VDC
        // ส่ง ผ่าน 3G  ไปที่ web host เป็น txt    เข้าไปที่ IoT server
        // ไฟเลี้ยง ใช่ แบต ลิเธียม 3.7 v bootup 5.5 volt จ่ายตัว sensor ชาร์จเจอร์ 5V

        //console.log("Data from %s : %s", remoteAddress,data);
        var buffer = Buffer.from(data);

        if(data != null || data != null )
        {         
            allFunc.insertDataIoT(data);    
            allFunc.insertDataInLog(data);        
        }
      
    })

    socket.once("close" , function(){
        console.log("Connection from %s closed", remoteAddress);
    });
    socket.on("error" , function(){
        console.log("Connection %s error : %s" , remoteAddress);
    });
});

server.listen(port, function() {
    console.log('server listening port '+port);
});