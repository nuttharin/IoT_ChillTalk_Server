const axios = require('axios');
const MongoClient  = require('mongodb').MongoClient ;

require('dotenv').config()

const URL_MONGODB_IOT = process.env.URL_MONGODB +process.env.DATABASE_DATA_IOT ;
const api_iot = process.env.API_IOT ;


const insertDataIoT = async (data) =>{
    try
    {
        let dataiot = data.toString('utf-8').split('/');        
        //console.log(data)
        let dataInsert = {
            serialNumber : dataiot[0],
            pressure : dataiot[3],           
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

module.exports = {
    insertDataIoT
}