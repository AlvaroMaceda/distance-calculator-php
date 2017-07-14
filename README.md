Script to calculate distances using google map 

This is a kick working draft, but I need this working ASAP

## Installation

Clone the repository:
```git
git clone https://github.com/AlvaroMaceda/distance-calculator-php.git
``` 

You will need php installed on your system. No additional libraries are required.

## Usage

Change to the app directory and execute:

```bash
dcalculator.sh <API_KEY> destinations_file.csv [origin]
```

Where:
- **API_KEY**. Required. Google DistanceMatrix key. You can obtain one [here](https://developers.google.com/maps/documentation/distance-matrix/get-api-key).
 The free tier is about 2.500 request/day, so use them wisely.
- **destinations_file.csv**. Required. A CSV formatted file with destination data. It can contain
additional columns. Only the last or the last two ones will be used.
- **origin**. Optional. If you speciy this, all distances will be calculated from this origin

### destinations_file.csv

This file contains a list of fields in CSV format. If [origin] is specified, the application 
will use the last field as the destination for this line, and will calculate all the distances 
from the same origin to the destination of each line.

For example:
TO-DO

If [origin] is not specified, the last two columns will be used: the penultimate one will be
the origin, and the last one the destination. For example:

TO-DO

## Development

TO-DO

Test and refactoring are needed.