import faker from 'faker';
import { v4 as uuidv4 } from 'uuid';

const arrayElement = faker.random.arrayElement;

function generateFakeProduct(){
  
  return {
    "name": faker.hacker.noun() + ' ' + faker.datatype.number({max: 1000, min: 10}),
    "price": parseFloat((Math.random() * (100 - 1) + 1).toFixed(2)),
    "unitsInStock": arrayElement([1,2,3,4,5]),
    "category": arrayElement(['Category 1','Category 2','Category 3']),
    "description": faker.lorem.paragraphs(),
    "valoration": arrayElement([1,2,3,4,5]),
    "aditionalInfo": arrayElement([1,2,3,4,5]) === 5 ? faker.hacker.phrase() : null ,
    "sku": arrayElement([1,2,3,4,5]) === 5 ? uuidv4() : null,
    "tags": [
      faker.system.fileType(),
      faker.system.fileType(),
    ],
    "images": [
      faker.internet.avatar(),
      faker.internet.avatar(),
      faker.internet.avatar(),
    ]
  }
  
}

export default {
  generateFakeProduct
}