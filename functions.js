const axios = require('axios');
const MongoClient  = require('mongodb').MongoClient ;
const { Double } = require("mongodb");
const fs = require('fs');


require('dotenv').config()

const URL_MONGODB_IOT = process.env.URL_MONGODB +process.env.DATABASE_DATA_IOT ;
const api_iot = process.env.API_IOT ;


const insertDataIoT = async (data) =>{
    try
    {
        //let dataiot = data.toString('utf-8').split('/'); 
        // if(data.toString('utf-8').split('\n')[0])
        // {

        // } 
        console.log(data.toString('utf-8'))
        console.log(data.toString('utf-8').split('\n'))  
        console.log(data.toString('utf-8').split('\n')[0].split(' '))
        console.log(data.toString('utf-8').split('\n')[0].split(' ')[1].split('/'))
        var arraycontainsturtles = await arrayContains(data.toString('utf-8').split('\n'),"\u0003") ;
        console.log(arraycontainsturtles);

        if(
            (data.toString('utf-8').split('\n')[0].split(' ')[1].split('/').length > 2)
             && arrayContains(data.toString('utf-8').split('\n'),"\u0003")
             && arrayContains(data.toString('utf-8').split('\n'),"\u0001")
             && arrayContains(data.toString('utf-8').split('\n'),"\u0000")
        )
        {
            let dataiot = data.toString('utf-8').split('\n');
            dataiot = dataiot[0].split(' ')[1].split('/');
            // console.log(dataiot);
            //console.log(data)
            let dataInsert = {
                serialNumber : dataiot[1],
                pressure : dataiot[4],           
            };
            let newDate = new Date();  
            console.log(data);
            MongoClient.connect(URL_MONGODB_IOT,function(err,db){
                let dbo = db.db(process.env.DATABASE_DATA_IOT);

                dbo.collection("tb_test")
                .insertOne( { 
                    serialNumber : dataInsert.serialNumber, pressure : Double(dataInsert.pressure), dateTime : newDate 
                } ,(err,result) =>
                {
                    if(err)
                    {
                        console.log("error")
                        throw err;
                    }
                    else
                    {
                        console.log("insert complete");
                        // res.status(200).json({
                        //     status : "success",
                        //     data : ""
                        // });

                    }
                });
            }); 
            insertDataInLog(data);
             
        }
        else{
            insertDataInLog(data);
        }

        

        // axios({
        //     method: 'post',
        //     url: api_iot + '/gas/post/collectPressureIoT',
        //     data: {
        //       dataInsert
        //     }
        // })
        // .then(function (response) {
        //     //console.log(response.data);
        // })
        // .catch(function (error) {
        //     //console.log(error);
        // });

            

    }
    catch(error)
    {
        console.log(error);
    }
}

const insertDataInLog = async (data) =>{
    // fs.writeFile('./log/logInsert.txt',data,(err) => {

    //     if(err) throw err;

    //     console.log('Lyric saved!');
    // });
    let d = new Date();
    let nameFile = 'logInsert'+d.getDate()+"-"+d.getMonth()	+"-"+d.getFullYear()+".txt"
    fs.appendFile('./log/'+nameFile, '\n'+data + "        " + (new Date).toString() , (err) => {
        if (err) throw err;
        console.log('insert log');
    });
}

arrayContains = async (array , str) => {
    for (let i = 0; i < array.length; i++) {       
        if(array[i].search(str) != -1)
        {
            return true;
        }
    }
    return false ;
   
}

module.exports = {
    insertDataIoT,
    insertDataInLog
}