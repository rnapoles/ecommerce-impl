import faker from 'faker';
import { v4 as uuidv4 } from 'uuid';

const randomElement = faker.random.arrayElement;

function generateFakeProduct(){
  
  return {
    "name": faker.hacker.noun() + ' ' + faker.datatype.number({max: 1000, min: 10}),
    "price": parseFloat((Math.random() * (100 - 1) + 1).toFixed(2)),
    "unitsInStock": randomElement([1,2,3,4,5]),
    "category": randomElement(['Category 1','Category 2','Category 3']),
    "description": faker.lorem.paragraphs(),
    "valoration": randomElement([1,2,3,4,5]),
    "aditionalInfo": randomElement([1,2,3,4,5]) === 5 ? faker.hacker.phrase() : null ,
    "sku": randomElement([1,2,3,4,5]) === 5 ? uuidv4() : null,
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