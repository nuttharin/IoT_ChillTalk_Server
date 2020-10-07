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
        let dataiot = data.toString('utf-8').split('\n');
        dataiot = dataiot[0].split(' ')[1].split('/');
        console.log(dataiot);

        //console.log(data)
        let dataInsert = {
            serialNumber : dataiot[1],
            pressure : dataiot[4],           
        };

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

        let newDate = new Date();  
        data = dataInsert ;
        console.log(data);
        MongoClient.connect(URL_MONGODB_IOT,function(err,db){
            let dbo = db.db(process.env.DATABASE_DATA_IOT);

            dbo.collection("tb_test")
            .insertOne( { 
                serialNumber : data.serialNumber, pressure : Double(data.pressure), dateTime : newDate 
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

    fs.appendFile('./log/logInsert.txt', '\n'+data + "        " + (new Date).toString() , (err) => {
        if (err) throw err;
        console.log('The lyrics were updated!');
    });
}

module.exports = {
    insertDataIoT,
    insertDataInLog
}