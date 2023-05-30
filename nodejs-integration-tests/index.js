import figures from 'figures';
import axios from 'axios';
import chalk from 'chalk';
import EventEmitter from 'events';
import cliProgress from 'cli-progress';
import colors from 'ansi-colors';
import Table from 'cli-table';
import faker from 'faker';
import Utils from './utils.js';


//const axios = require('axios');
//const figures = require('figures');
//const cliProgress = require('cli-progress');
//const colors = require('ansi-colors');

const BASE_URL = 'https://localhost:12000/api';

//it disables SSL validation
process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0';

const httpClient = axios.create({
  baseURL: BASE_URL,
  //timeout: 5000,
  //headers: {'X-Custom-Header': 'foobar'}
});
httpClient.step = 0;


// Add a response interceptor
httpClient.interceptors.request.use(
  (config) => {

    if(httpClient.JWT_TOKEN){
      config.headers.Authorization = "Bearer " + httpClient.JWT_TOKEN;
    }
    
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Add a response interceptor
httpClient.interceptors.response.use(
  function (response) {
    return response;
  },
  function (error) {
    return Promise.reject(error);
  }
);

const eventBus = new EventEmitter();

const loginData = {
  "username": "admin@localhost.loc",
  "password": "1234"
};

const newUserData = {
  "email": faker.internet.email().toLowerCase(),
  "password": "1234"
};

const shareData = {};

const RequestConfig = {
  headers: {
    'Content-Type': 'application/json; charset=utf-8',
  },
};

const EndPoints = [
  {
    url: '/login',
    method: 'post',
    config: RequestConfig,
    msg: 'Check login',
    data: loginData,
    expectedResponse: {
      token: true
    },
    postProcess: (response) => {

      if(!response){
        return;
      }
      
      const token = response.data.token;
      if(!token){
        return;
      }
      
      const jwt = JSON.parse(Buffer.from(token.split('.')[1], 'base64').toString());
      let expire = dateDiffString(new Date(jwt.exp * 1000), new Date()) ;
      console.log(chalk.green(figures.tick), `Token expire in ${expire}`);
    },
    expectedStatus: 200,
    debugResponse: false,
  },
  {
    url: '/user/register',
    method: 'post',
    config: RequestConfig,
    msg: 'Validate invalid user register json',
    data: loginData,
    expectedResponse: {
      success: false
    },
    postProcess: (response) => {
      //console.log(response.data)
    },
    expectedStatus: 400,
    debugResponse: false,
    skip: false,
  },
  {
    url: '/user/register',
    method: 'post',
    config: RequestConfig,
    msg: 'Validate user register',
    data: newUserData,
    postProcess: (response) => {
      const data = response.data;
      if(data.success){
        shareData.user = data.payload;
      }
    },
    expectedResponse: {
      success: true
    },
    expectedStatus: 201,
    debugResponse: false,
    skip: false,
  },
  {
    url: '/user/register',
    method: 'post',
    config: RequestConfig,
    msg: 'Validate unique user register',
    data: newUserData,
    expectedResponse: {
      success: true
    },
    expectedStatus: 400,
    debugResponse: false,
    skip: false,
  },
  {
    url: '/user/update/{id}',
    method: 'patch',
    config: RequestConfig,
    msg: 'Validate user update',
    data: {
      password: '123456/*'
    },
    expectedResponse: {
      success: true
    },
    preProcess: (self) => {
      
      const user = shareData.user;
      
      if(user){
        self.url = self.url.replace('{id}', user.id);
        self.data = user;
      }
    },
    expectedStatus: 200,
    debugResponse: false,
    skip: false,
  },
  {
    url: '/user/list',
    method: 'get',
    config: {
      ...RequestConfig,
      params: {
        start: 0,
        total: 5,
      }
    },
    msg: 'Validate list user endpoint',
    expectedResponse: {
      success: true
    },
    expectedStatus: 200,
    debugResponse: false,
    skip: false,
  },
  {
    url: '/product/create',
    method: 'post',
    data: Utils.generateFakeProduct(),
    msg: 'Validate create product endpoint',
    expectedResponse: {
      success: true
    },
    postProcess: (response) => {
      const data = response.data;
      if(data.success){
        shareData.product = data.payload;
      }
    },
    expectedStatus: 201,
    debugResponse: false,
    skip: false,
  },
  {
    url: '/product/{sku}',
    method: 'get',
    msg: 'Validate read product endpoint',
    expectedResponse: {
      success: true
    },
    preProcess: (self) => {
      const product = shareData.product;
      if(product){
        self.url = self.url.replace('{sku}', product.sku);
      }
    },
    expectedStatus: 200,
    debugResponse: false,
    skip: false,
  },
  {
    url: '/product/update/{id}',
    method: 'patch',
    msg: 'Validate update product endpoint',
    expectedResponse: {
      success: true
    },
    preProcess: (self) => {
      const product = shareData.product;
      if(product){
        self.url = self.url.replace('{id}', product.id);
        self.data = product;
      }
    },
    expectedStatus: 200,
    debugResponse: false,
    skip: false,
  },
  {
    url: '/product/search',
    method: 'get',
    config: {
      ...RequestConfig,
      params: {
        query: 'valoration = 5'
      }
    },
    msg: 'Validate search products endpoint',
    expectedResponse: {
      success: true
    },
    expectedStatus: 201,
    debugResponse: false,
    skip: false,
  },
  {
    url: '/product/delete/{id}',
    method: 'delete',
    msg: 'Validate delete product endpoint',
    preProcess: (self) => {
      const product = shareData.product;
      if(product){
        self.url = self.url.replace('{id}', product.id);
        self.data = product;
      }
    },
    expectedResponse: {
      success: true
    },
    expectedStatus: 200,
    debugResponse: false,
    skip: false,
  },
];

const testData = EndPoints.filter(it => !!it.skip === false);

const SIMPLE_METHODS = ['get', 'head', 'delete'];
const processResult = (result, current) => {
  
  let status = 0;
  let data = {};
  let response = result;

  if(result instanceof Error){

    const request = result.request;
    response = result.response;

    if (response) {
      status = response.status;
      data = response.data;
    } else if (request) {
      //('Network Error');
    } else {
      //(error.message);
    }

  } else {
      status = result.status;
      data = result.data;
  }

  let success = true;
  const expectedStatus = current.expectedStatus;

  if(expectedStatus !== undefined){
    success = expectedStatus === status;
  }

  if(success){
    console.log(chalk.green(figures.tick), current.msg);
  } else {
    console.log(chalk.red(figures.cross), current.msg, current.expectedStatus, status)
  }


  if(current.debugResponse){
    console.log(data);
  }

  if(current.postProcess){
    current.postProcess(response);
  }

  const step = ++httpClient.step;

  if(step < testData.length){
    
    const it = testData[step];

    if(it.preProcess){
      it.preProcess(it);
    }
  
    if(SIMPLE_METHODS.includes(it.method)){
      httpClient[it.method](it.url, it.config)
      .then((response) => {
        processResult(response, it);
      }).catch((error)=>{
        processResult(error, it);
      });
    } else {
      httpClient[it.method](it.url, it.data, it.config)
      .then((response) => {
        processResult(response, it);
      }).catch((error)=>{
        processResult(error, it);
      });
    }

  }

};

const dateDiffString = (future, now) => {
  
  // get total seconds between the times
  let delta = Math.abs(future - now) / 1000;

  // calculate (and subtract) whole days
  let days = Math.floor(delta / 86400);
  delta -= days * 86400;

  // calculate (and subtract) whole hours
  let hours = Math.floor(delta / 3600) % 24;
  delta -= hours * 3600;

  // calculate (and subtract) whole minutes
  let minutes = Math.floor(delta / 60) % 60;
  delta -= minutes * 60;

  // what's left is seconds
  let seconds = delta % 60;  // in theory the modulus is not required

  let result = [];
  if(days){
    result.push(`${days} days`);
  }
  
  if(hours){
    result.push(`${hours} hours`);
  }
  
  if(minutes){
    result.push(`${minutes} minutes`);
  }
  
  if(seconds){
    result.push(`${parseInt(seconds)} seconds`);
  }


  return result.join(' ');
}

httpClient.post('/login', loginData)
.then((response) => {
  const token = response.data.token;
  httpClient.JWT_TOKEN = token; 

  return response;
})
.then((response) => {
  processResult(response, testData[0]);    
})
.catch((error)=>{
  processResult(error, testData[0]);
});


var table = new Table({ head: ["", "Top Header 1", "Top Header 2"] });

table.push(
    { 'Left Header 1': ['Value Row 1 Col 1', 'Value Row 1 Col 2'] }
  , { 'Left Header 2': ['Value Row 2 Col 1', 'Value Row 2 Col 2'] }
);

//console.log(table.toString());
//console.log(b1.value);
//console.log(b1.total);