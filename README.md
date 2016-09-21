# importr

[![Build Status](https://travis-ci.org/sirdiego/importr.svg?branch=master)](https://travis-ci.org/sirdiego/importr) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sirdiego/importr/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sirdiego/importr/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/sirdiego/importr/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sirdiego/importr/?branch=master)

The importr can be used to create a bridge between TYPO3 entities and Excel, CSV or other text files.

## Installation

After installing the extension via one of the possible methods you need to add the Scheduler Task to your tasks.
* Extbase CommandController Task (extbase) - importr:import:initializeservicemanager

The frequency should be as often as possible (e.g. `* * * * *`), this way the Importr can be as precise as possible with the import timings.
 
TODO: Screenshot

### TYPO3 Composer Mode
`composer require diego/importr`
### Via Extension Manager
TODO: Screenshot
## Usage

After installing the extension and adding the Scheduler Task you can start configuring your possible imports. These are called _Strategies_ and should be placed on the root of your TYPO3 installation.

A _Strategy_ consists of three main parts. The **general configuration**, the **resource configuration** and the **target configuration**. Every import needs to know from what resource it gets its data (resource) and where to put it (target).

By default the extension ships with an Excel (HDNET\Importr\Service\Resources\Excel), CSV (HDNET\Importr\Service\Resources\Csv) resources and Extbase model (HDNET\Importr\Service\Targets\ExtbaseModel) and database record (HDNET\Importr\Service\Targets\DbRecord) targets.

A simple _Strategy_ can look like this:

**Configuration**
```yaml
before:
  updateInterval: 100 # Sets th
after:
  createImport:
    1:
      importId: 1
      start: tomorrow 0400
      filepath: uploads/my_import.csv
```
**Resources**
```yaml
HDNET\Importr\Service\Resources\Csv: 
  skipRows: 1
  delimiter: ,
```
**Targets**
```yaml
HDNET\Importr\Service\Targets\ExtbaseModel: 
  model: VENDOR\MyExt\Domain\Model\MyModel
  repository: VENDOR\MyExt\Domain\Repository\MyRepository
  pid: 123
  mapping:
    0: myPropertyA
    1: myPropertyB
    8: myPropertyD
```
