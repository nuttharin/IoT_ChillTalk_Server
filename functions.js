const axios = require('axios');
require('dotenv').config()
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
        axios({
            method: 'post',
            url: api_iot + '/gas/post/collectPressureIoT',
            data: {
              dataInsert
            }
        })
        .then(function (response) {
            //console.log(response.data);
        })
        .catch(function (error) {
            //console.log(error);
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